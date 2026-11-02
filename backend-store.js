/* Integração do catálogo e checkout público com a API PHP. */
'use strict';
let apiCsrf='';
async function backendRequest(path,options={}){
  const headers={'Accept':'application/json',...(options.body?{'Content-Type':'application/json'}:{}),...(options.headers||{})};
  if(options.method&&options.method!=='GET'&&apiCsrf)headers['X-CSRF-Token']=apiCsrf;
  const response=await fetch('api/'+path,{...options,headers,credentials:'same-origin',cache:'no-store'});
  const data=await response.json().catch(()=>({error:'Resposta inválida do servidor.'}));
  if(!response.ok)throw new Error(data.error||`Erro HTTP ${response.status}`);
  if(data.csrf)apiCsrf=data.csrf;
  return data;
}
function productImageUrl(value){
  const url=String(value||'');
  return url.startsWith('/assets/')?new URL('.'+url,document.baseURI).href:url;
}
async function loadSecureCatalog(){
  try{
    $('#catalog-status').textContent='Carregando catálogo…';
    const data=await backendRequest('products.php');
    $('#catalog-status').textContent=(data.products||[]).length?'':'Nenhum produto cadastrado. Prepare o banco ou cadastre os produtos no painel.';
    prods=(data.products||[]).filter(p=>Number(p.active)===1).map(p=>({id:Number(p.id),n:String(p.name),c:String(p.category),p:Number(p.price),s:Number(p.stock),m:3,a:'#c2456b',b:'#f4a9b8',d:String(p.description||''),i:productImageUrl(p.image_url),active:Number(p.active)}));
    // Remove do carrinho itens inativos ou removidos e não confie no estoque salvo no navegador.
    for(const id of Object.keys(cart)){const product=prods.find(p=>p.id===Number(id));if(!product||product.active!==1)delete cart[id];else cart[id]=Math.min(Number(cart[id])||0,product.s)}
    CATS=[...new Set(prods.map(p=>p.c))];cats();sv('c',cart);shop();hdr();
  }catch(error){$('#catalog-status').textContent=error.message;prods=[];cart={};CATS=[...new Set(prods.map(p=>p.c))];cats();sv('c',cart);shop();hdr();const msg=document.querySelector('#ce');if(msg)msg.textContent='Não foi possível carregar o catálogo seguro. Verifique a configuração do backend PHP/PostgreSQL.';$('#gr').replaceChildren();$('#cnt').textContent='Catálogo indisponível';console.error('Catálogo backend:',error.message)}
}
// O total final e a disponibilidade são recalculados no servidor durante a gravação.
$('#ck').onclick=async()=>{
  const c=calc(),err=$('#ce');err.textContent='';
  if(!c.it.length){err.textContent='O carrinho está vazio ou o catálogo não foi carregado.';return}
  const customerName=$('#gn2').value.trim(),customerPhone=$('#gt').value.replace(/\D/g,'');if(!customerName||customerPhone.length<10||customerPhone.length>13){err.textContent='Informe nome e telefone com DDD (10 a 13 dígitos) para o pedido.';return}
  if(!$('#ad').value.trim()||!$('#dt').value){err.textContent='Informe o endereço e a data da entrega.';return}
  const wm=wcart()+'\nPagamento: '+$('#pg').value;
  const payload={customer_name:customerName,customer_phone:customerPhone,delivery_address:$('#ad').value.trim(),delivery_date:$('#dt').value,payment_method:$('#pg').value,note:note,items:c.it.map(x=>({product_id:Number(x.p.id),quantity:Number(x.q)}))};
  const button=$('#ck');button.disabled=true;err.textContent='Registrando pedido com o servidor…';
  try{
    if(!apiCsrf){const token=await backendRequest('csrf.php');apiCsrf=token.csrf}
    const result=await backendRequest('orders.php',{method:'POST',body:JSON.stringify(payload)});
    cart={};sv('c',cart);err.textContent='';$('#dt').value='';asked=0;NT={p:'',m:'',d:'',k:'n'};$('#np').value=$('#nt').value=$('#nd').value='';$('#nb').hidden=true;nrender();$('#dc').close();hdr();await loadSecureCatalog();
    $('#ow').dataset.m=('Pedido #'+result.order_id+'\n'+wm).replace(/Subtotal — entrega não incluída: [^\n]*/, 'Subtotal — entrega não incluída: '+R(Number(result.subtotal)));$('#owm').textContent=`Pedido #${result.order_id} registrado. Subtotal: ${R(Number(result.subtotal))}, sem entrega. O vendedor informará o frete pelo WhatsApp conforme o bairro.`;$('#do').showModal();navigator.vibrate?.([30,40,30]);
  }catch(error){err.textContent=error.message;await loadSecureCatalog()}
  finally{button.disabled=false;}
};
loadSecureCatalog();

function setSession(user){
  me=user?{id:user.id,n:user.name,e:user.email,t:user.phone||'',r:user.role}:null;
  if(me){$('#gn2').value=me.n;$('#gt').value=me.t;}
  hdr();
}
async function ensureCsrf(){if(!apiCsrf)await backendRequest('csrf.php');}
$('#ub').onclick=()=>me?acct():openLogin();
$('#lf').onsubmit=async event=>{
  event.preventDefault();const b=$('#sb');b.disabled=true;$('#er').textContent='';
  try{
    await ensureCsrf();
    const register=mode==='up';
    const payload=register?{name:$('#nm').value.trim(),email:$('#register-email').value.trim(),phone:$('#tf').value,password:$('#pw').value}
                          :{email:$('#id').value.trim(),password:$('#pw').value};
    const data=await backendRequest('auth.php?action='+(register?'register':'login'),{method:'POST',body:JSON.stringify(payload)});
    setSession(data.user);$('#dl').close();$('#lf').reset();toast('Olá, '+me.n+'!');
  }catch(e){$('#er').textContent=e.message;}finally{b.disabled=false;}
};
$('#fa').onsubmit=async event=>{
  event.preventDefault();$('#ar').textContent='';
  try{
    await ensureCsrf();
    const data=await backendRequest('auth.php?action=profile',{method:'POST',body:JSON.stringify({name:$('#an').value,email:$('#ae').value,phone:$('#at').value,current_password:$('#ac').value,password:$('#ap').value})});
    setSession(data.user);$('#da').close();toast('Dados atualizados.');
  }catch(e){$('#ar').textContent=e.message;}
};
$('#lo').onclick=async()=>{
  try{await ensureCsrf();await backendRequest('auth.php?action=logout',{method:'POST',body:'{}'});setSession(null);$('#da').close();$('#gn2').value=$('#gt').value='';toast('Você saiu da conta.');}
  catch(e){$('#ar').textContent=e.message;}
};
// Login via Google/WhatsApp exige provedores externos; os botões simulados foram retirados da interface.
$('#gb').onclick=$('#wb').onclick=()=>{};
$('#au').closest('label').hidden=true;
backendRequest('auth.php?action=me').then(data=>setSession(data.user)).catch(e=>toast(e.message));

// Atalhos para selecionar elementos HTML, escapar texto e formatar preços em reais.
const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)],rnd=Math.random;
const esc=s=>String(s).replace(/[&<>"']/g,c=>'&#'+c.charCodeAt(0)+';'),R=n=>n.toLocaleString('pt-BR',{style:'currency',currency:'BRL'});
// Apenas carrinho, favoritos e rascunho do cartão ficam no navegador.
const K='jm_pg_',ld=(k,d)=>{try{const v=localStorage.getItem(K+k);return v?JSON.parse(v):d}catch{return d}},sv=(k,v)=>{try{localStorage.setItem(K+k,JSON.stringify(v))}catch{}};
const I=(n,c='')=>`<svg class="ic ${c}" aria-hidden="true"><use href="#i-${n}"/></svg>`,CI={'Todas':'grid','Buquês':'flower','Arranjos':'heart','Cestas':'gift','Rosas e girassóis':'tulip','Adicionais':'sparkle'};
let LST=[],otp='';const CATS0=['Buquês','Arranjos','Cestas','Rosas e girassóis','Adicionais'];let CATS=ld('v',0)<3?CATS0.slice():ld('cats',CATS0.slice());
// Catálogo carregado exclusivamente da API PostgreSQL.

const ST0={nm:'J&M Floricultura',wa:'5598988884805',ad:'São José de Ribamar - MA',hr:''};let ST={...ST0,...window.JM_STORE},prods=[],users=[],cart=ld('c',{}),orders=[],me=null,fav=ld('f',[]),note='',NT=ld('nt',{p:'',m:'',d:'',k:'n'}),cat='Todas',mode='up';
// Segurança: contas administrativas locais antigas são removidas; admin deve usar /admin.php com sessão PHP.
me=null;
/* ilustrações em SVG: uma cor de pétala e uma de miolo por flor */
// Desenha pétalas/elementos vetoriais usados nas ilustrações de produtos.
const bl=(x,y,r,a,b)=>{let p='';for(let i=0;i<6;i++)p+=`<ellipse cx="${x}" cy="${y-r*.55}" rx="${r*.42}" ry="${r*.62}" fill="${a}" stroke="${b}" stroke-opacity=".35" transform="rotate(${i*60} ${x} ${y})"/>`;return p+`<circle cx="${x}" cy="${y}" r="${r*.26}" fill="${b}"/>`};
const bq=(a,b)=>`<svg viewBox="0 0 120 124" aria-hidden="true"><g stroke="#4d6b4a" stroke-width="2.5" fill="none" stroke-linecap="round"><path d="M60 112V66M60 112L38 60M60 112l24-52M60 112L26 82M60 112l36-30"/></g><g fill="#5f8a5a"><ellipse cx="44" cy="84" rx="12" ry="5" transform="rotate(-30 44 84)"/><ellipse cx="78" cy="86" rx="12" ry="5" transform="rotate(30 78 86)"/></g><path d="M28 74L60 122L92 74Z" fill="#c9a27a"/>${[[60,40,22],[36,54,16],[84,52,16],[24,76,11],[96,76,11]].map(q=>bl(...q,a,b)).join('')}</svg>`;
const art=(a,b,c)=>c==='Plantas de casa'||c==='Orquídeas'?`<svg viewBox="0 0 120 124" aria-hidden="true">${[-62,-38,-14,12,36,60].map(r=>`<ellipse cx="60" cy="52" rx="9" ry="27" fill="${c==='Orquídeas'?'#4d8a5a':a}" stroke="${b}" stroke-opacity=".3" transform="rotate(${r} 60 82)"/>`).join('')}${c==='Orquídeas'?bl(60,30,16,a,b)+bl(88,46,11,a,b):''}<path d="M40 84h40l-6 34H46z" fill="#c9825b"/></svg>`:c==='Vasos e cuidados'?`<svg viewBox="0 0 120 124" aria-hidden="true"><path d="M48 30h24c0 16 16 22 16 54 0 20-9 34-28 34S32 104 32 84c0-32 16-38 16-54z" fill="${a}" stroke="${b}" stroke-width="3"/><path d="M36 76h48" stroke="${b}" stroke-width="5"/></svg>`:bq(a,b);
const pic=p=>p.i?`<img src="${esc(p.i)}" alt="${esc(p.n)}" loading="lazy">`:art(p.a,p.b,p.c);
// Redimensiona imagens enviadas pelo painel para reduzir o tamanho armazenado.
const shrink=f=>new Promise(r=>{const fr=new FileReader;fr.onload=()=>{const i=new Image;i.onload=()=>{const k=Math.min(1,720/Math.max(i.width,i.height)),c=document.createElement('canvas');c.width=i.width*k;c.height=i.height*k;const g=c.getContext('2d');g.fillStyle='#fff';g.fillRect(0,0,c.width,c.height);g.drawImage(i,0,0,c.width,c.height);r(c.toDataURL('image/jpeg',.82))};i.onerror=()=>r('');i.src=fr.result};fr.onerror=()=>r('');fr.readAsDataURL(f)});
/* pétalas e corações: sprites pré-renderizados, movimento por tempo (independe do monitor) e profundidade */
const cv=$('#fx'),cx=cv.getContext('2d'),dpr=Math.min(devicePixelRatio||1,2);let W,Hh,P=[],on=!matchMedia('(prefers-reduced-motion:reduce)').matches,raf=0,last=0,wind=0;
// Prepara o canvas de efeitos para o tamanho atual da janela.
const rs=()=>{W=innerWidth;Hh=innerHeight;cv.width=W*dpr;cv.height=Hh*dpr;cx.setTransform(dpr,0,0,dpr,0,0)};addEventListener('resize',rs);rs();
// Cria sprites de pétalas e corações para a animação decorativa.
const sprite=(c1,c2,hr)=>{const s=document.createElement('canvas'),g=s.getContext('2d');s.width=s.height=64;g.translate(32,hr?36:32);
const gr=g.createRadialGradient(0,-6,2,0,0,30);gr.addColorStop(0,c1);gr.addColorStop(1,c2);g.fillStyle=gr;g.shadowColor=c2;g.shadowBlur=6;g.beginPath();
if(hr){const k=22;g.moveTo(0,.35*k);g.bezierCurveTo(-1.1*k,-.4*k,-.5*k,-1.1*k,0,-.45*k);g.bezierCurveTo(.5*k,-1.1*k,1.1*k,-.4*k,0,.35*k)}
else{g.moveTo(0,-24);g.bezierCurveTo(20,-16,18,16,0,24);g.bezierCurveTo(-18,16,-20,-16,0,-24)}
g.fill();if(!hr){g.shadowBlur=0;g.globalAlpha=.25;g.strokeStyle=c2;g.beginPath();g.moveTo(0,-18);g.quadraticCurveTo(2,0,0,18);g.stroke()}return s};
const PS=[['#fff1f3','#f2a7b9'],['#ffe3e8','#e98aa3'],['#fff6f0','#f6c3b0'],['#fbd0d6','#d9708f']].map(a=>sprite(...a)),HS=sprite('#ffc2d1','#ff6b93',1);
// Gera uma pétala com posição, velocidade e rotação aleatórias.
const petal=()=>{const z=rnd();return{t:0,z,x:rnd()*W,y:-30,s:11+z*15,vy:9+z*20,sw:18+rnd()*28,ph:rnd()*6.3,w:.5+rnd()*.7,r:rnd()*6.3,vr:(rnd()-.5)*.5,a:.4+z*.4,k:PS[rnd()*4|0]}};
// Gera um coração animado, inclusive para os efeitos de clique.
const heart=(x,y,b)=>({t:1,x,y,s:12+rnd()*14,vy:b?-(40+rnd()*60):-(18+rnd()*22),vx:b?(rnd()-.5)*90:0,ph:rnd()*6.3,w:1+rnd(),sw:b?6:14,r:(rnd()-.5)*.4,age:0,life:b?2.4:7});
const burst=(x,y)=>{if(on)for(let i=0;i<12;i++)P.push(heart(x,y,1))};
// Loop de animação: atualiza e desenha pétalas/corações no canvas.
function loop(t){const dt=Math.min(.05,(t-last)/1000||0);last=t;cx.clearRect(0,0,W,Hh);wind=Math.sin(t/7000)*9;
if(P.filter(q=>!q.t).length<(W<600?8:14)&&rnd()<.9*dt)P.push(petal());
const am=$('#amor').getBoundingClientRect();if(am.top<Hh&&am.bottom>0&&rnd()<1.4*dt)P.push(heart(am.left+rnd()*am.width,Math.min(am.bottom,Hh)-10));
P=P.filter(q=>{let a;q.ph+=q.w*dt;
if(q.t){q.age+=dt;if(q.age>q.life)return false;q.x+=(q.vx+Math.cos(q.ph)*q.sw*q.w)*dt;q.vx*=Math.pow(.3,dt);q.y+=q.vy*dt;a=.85*Math.min(1,q.age*2.5)*(1-q.age/q.life)}
else{q.x+=(Math.cos(q.ph)*q.sw*q.w+wind*(.4+q.z))*dt;q.y+=q.vy*dt;q.r+=q.vr*dt;if(q.y>Hh+30||q.x>W+60||q.x<-60)return false;a=q.a*Math.min(1,(q.y+30)/90)*Math.min(1,(Hh+30-q.y)/160)}
cx.save();cx.translate(q.x,q.y);cx.globalAlpha=Math.max(0,a);
if(q.t){cx.rotate(q.r);cx.drawImage(HS,-q.s/2,-q.s/2,q.s,q.s)}
else{cx.rotate(q.r+Math.sin(q.ph)*.4);cx.scale(.55+.45*Math.abs(Math.cos(q.ph*.8)),1);cx.drawImage(q.k,-q.s/2,-q.s/2,q.s,q.s)}
cx.restore();return true});raf=requestAnimationFrame(loop)}
// Inicia ou reinicia os efeitos decorativos de fundo.
const run=()=>{cancelAnimationFrame(raf);last=performance.now();P=[];cx.clearRect(0,0,W,Hh);if(on){for(let i=0;i<6;i++){const q=petal();q.y=rnd()*Hh;P.push(q)}raf=requestAnimationFrame(loop)}};
$('#fxb').onclick=ev=>{on=!on;ev.currentTarget.setAttribute('aria-pressed',on);run()};
/* interface */
let tt;const toast=m=>{const t=$('#toast');t.textContent=m;t.classList.add('on');clearTimeout(tt);tt=setTimeout(()=>t.classList.remove('on'),2800)};
// Calcula itens do carrinho, subtotal, frete e total do pedido.
const calc=()=>{const it=Object.entries(cart).map(([i,q])=>({p:prods.find(x=>x.id==i),q})).filter(x=>x.p&&x.q>0),sub=it.reduce((a,x)=>a+x.p.p*x.q,0);return{it,sub,fr:null,tot:sub}};
// Atualiza o cabeçalho: quantidade no carrinho e botões da conta.
function hdr(){const n=Object.values(cart).reduce((a,b)=>a+b,0);$('#cn').textContent=n;$('#cb').setAttribute('aria-label','Carrinho, '+n+' itens');$('#ul').textContent=me?me.n.split(' ')[0]:'Entrar';$('#ub').title=me?'Minha conta':'';$('#sm').hidden=true;$('#gf').hidden=false;$('#cbar').hidden=!n;$('#cbt').textContent=n+(n===1?' item, ':' itens, ')+R(calc().sub);nrender()}
// Monta o HTML de um cartão de produto, com preço, estoque e ações.
const card=(p,i)=>{const f=fav.includes(p.id);return`<article class="card" style="--i:${i}"><button class="vw" tabindex="-1" aria-hidden="true" data-act="view" data-id="${p.id}"><span class="im">${pic(p)}</span></button>${p.s<1?'<span class="tag x">Esgotado</span>':p.s<=3?`<span class="tag">Restam ${p.s}</span>`:''}<button class="fv" data-act="fav" data-id="${p.id}" aria-pressed="${f}" aria-label="Favoritar ${esc(p.n)}">${f?'♥':'♡'}</button><div class="bd"><span class="cat">${I(CI[p.c]||'flower','s')} ${esc(p.c)}</span><h3><button class="vw" data-act="view" data-id="${p.id}">${esc(p.n)}</button></h3><p>${esc(p.d)}</p><div class="rw"><span class="pr">${R(p.p)}</span><button class="btn" data-act="add" data-id="${p.id}" ${p.s<1?'disabled':''}>${p.s<1?'Esgotado':I('cart','s')+' Adicionar'}</button></div></div></article>`};
// Filtra e renderiza o catálogo conforme busca, categoria e disponibilidade.
function shop(){const q=$('#q').value.toLowerCase(),st=$('#st').checked;
const l=prods.filter(p=>(cat==='Todas'||p.c===cat)&&(p.n+p.d).toLowerCase().includes(q)&&(!st||p.s>0));LST=l.map(p=>p.id);
$('#cnt').textContent=l.length+(l.length===1?' item encontrado':' itens encontrados');
const gs=cat==='Todas'?CATS.map(c=>[c,l.filter(p=>p.c===c)]).filter(g=>g[1].length):[[cat,l]];
$('#gr').innerHTML=l.length?gs.map(([c,a])=>`<section class="cg"><h3 class="ct">${I(CI[c]||'flower','s')} ${esc(c)} <small>${a.length}</small></h3><div class="grid">${a.map(card).join('')}</div></section>`).join(''):'<p>Nenhum item encontrado. Tente outra busca ou categoria.</p>'}
// Atualiza os botões de categorias na loja e no painel administrativo.
function cats(){$('#ch').innerHTML=['Todas',...CATS].map(c=>`<button class="chip" data-act="cat" data-c="${esc(c)}" aria-pressed="${c===cat}">${I(CI[c]||'flower','s')} ${esc(c)}</button>`).join('');
$('#tl').innerHTML=CATS.map(c=>`<button class="tile" data-act="cat" data-c="${esc(c)}" data-go="1">${I(CI[c]||'flower','l')}<b>${esc(c)}</b></button>`).join('');
$('[name=c]').innerHTML=CATS.map(c=>`<option>${esc(c)}</option>`).join('')}
// Renderiza o conteúdo do carrinho e recalcula os valores exibidos.
function cartUI(){const{it,sub,fr,tot}=calc();
$('#ci').innerHTML=it.length?'<small class="mu">Deslize um item para a esquerda para remover.</small>'+it.map(x=>`<div class="li" data-id="${x.p.id}"><div><b>${esc(x.p.n)}</b><br><small>${R(x.p.p)}</small></div><div class="qt"><button data-act="dec" data-id="${x.p.id}" aria-label="Menos uma ${esc(x.p.n)}">−</button><span>${x.q}</span><button data-act="inc" data-id="${x.p.id}" aria-label="Mais uma ${esc(x.p.n)}">+</button><button data-act="rm" data-id="${x.p.id}" aria-label="Remover ${esc(x.p.n)}">✕</button></div></div>`).join(''):'<p>Seu carrinho está vazio. Escolha um buquê para começar.</p>';
$('#ct').innerHTML=it.length?`<p class="mu">${note?'Cartão: “'+esc(note)+'”':'Sem recado no cartão.'}</p><div class="rw"><b>Subtotal — entrega não incluída</b><b class="pr">${R(sub)}</b></div><p class="mu">Entrega a combinar pelo WhatsApp. O valor varia conforme o bairro e será informado pelo vendedor antes de concluir a compra.</p>`:'';
$('#ck').disabled=!it.length}
const MIN=p=>p.m??3,bdg=p=>{const t=p.s<1?['Esgotado','x']:p.s<=MIN(p)?['Repor','lo']:p.s<=MIN(p)*2?['Atenção','md']:['Em dia','ok'];return`<span class="bd2 ${t[1]}">${t[0]}</span>`};
const stp=p=>`<div class="qt"><button data-act="sm" data-id="${p.id}" aria-label="Menos um em ${esc(p.n)}">−</button><input class="si" type="number" min="0" value="${p.s}" data-id="${p.id}" aria-label="Estoque de ${esc(p.n)}"><button data-act="sp" data-id="${p.id}" aria-label="Mais um em ${esc(p.n)}">+</button></div>`;
const sold=id=>orders.reduce((a,o)=>a+(o.it||[]).filter(i=>i.id===id).reduce((b,i)=>b+i.q,0),0);
let sfl='a',want=0;
// Atualiza o painel administrativo: indicadores, gráficos, estoque e pedidos.
function admin() {}
// Alterna as abas do painel administrativo.
const tab=t=>{$$('[data-tp]').forEach(x=>x.hidden=x.dataset.tp!==t);$$('[data-t]').forEach(b=>b.setAttribute('aria-pressed',b.dataset.t===t))};
$$('[data-t]').forEach(b=>b.onclick=()=>tab(b.dataset.t));
$$('[data-sf]').forEach(b=>b.onclick=()=>{sfl=b.dataset.sf;$$('[data-sf]').forEach(x=>x.setAttribute('aria-pressed',x===b));admin()});
// Alterna entre a visualização da loja e a área administrativa.
function go(v){if(v==='a'){location.href='admin.php';return;}document.body.classList.remove('ad');$('#cv').hidden=false;$('#av').hidden=true;}
function acct(){if(!me)return openLogin();$('#an').value=me.n;$('#ae').value=me.e;$('#at').value=me.t;$('#ac').value=$('#ap').value=$('#ar').textContent='';$('#da').showModal();}
const cm=()=>{$('#sml').hidden=true;$('#sk').setAttribute('aria-expanded',false)};
$('#sk').onclick=ev=>{ev.stopPropagation();const o=$('#sml').hidden;$('#sml').hidden=!o;$('#sk').setAttribute('aria-expanded',o)};
document.addEventListener('click',ev=>{if(!ev.target.closest('#sm'))cm()});addEventListener('keydown',ev=>{if(ev.key==='Escape')cm()});
document.addEventListener('click',ev=>{const t=ev.target.closest('[data-act]');if(!t)return;const a=t.dataset.act,id=+t.dataset.id,p=prods.find(x=>x.id===id);
if(a==='x')t.closest('dialog').close();
else if(a==='cat'){cat=t.dataset.c;$('#gr').classList.add('go');$$('.chip[data-act=cat]').forEach(c=>c.setAttribute('aria-pressed',c.dataset.c===cat));shop();if(t.dataset.go)$('#loja').scrollIntoView()}
else if(a==='fav'){const o=!fav.includes(id);fav=o?[...fav,id]:fav.filter(x=>x!==id);sv('f',fav);t.textContent=o?'♥':'♡';t.setAttribute('aria-pressed',o);if(o){const r=t.getBoundingClientRect();burst(r.left+20,r.top+20)}}
else if(a==='add'||a==='inc'){const n=(cart[id]||0)+1;if(n>p.s){const m='Só temos '+p.s+' em estoque.';if(a==='inc')$('#ce').textContent=m;else{t.closest('dialog')?.close();toast(m)}return}cart[id]=n;sv('c',cart);hdr();if(a==='inc')cartUI();else{navigator.vibrate?.(18);fly(t);if(!t.closest('dialog')){const h=t.innerHTML;t.innerHTML=I('check','s')+' Adicionado';setTimeout(()=>{t.innerHTML=h},1200)}t.closest('dialog')?.close();toast(p.n+' foi para o carrinho.');setTimeout(askNote,900)}}
else if(a==='dec'||a==='rm'){cart[id]=a==='rm'?0:(cart[id]||1)-1;if(!cart[id])delete cart[id];sv('c',cart);cartUI();hdr()}
else if(a==='view'){$('#dpb').innerHTML=`<span class="im">${pic(p)}</span><span class="cat">${I(CI[p.c]||'flower','s')} ${esc(p.c)}</span><h3 style="margin:0">${esc(p.n)}</h3><p style="margin:0">${esc(p.d)}</p>${p.x?`<p class="mu" style="margin:0">${esc(p.x)}</p>`:''}<small>${p.s<1?'Esgotado no momento.':p.s<=3?'Restam apenas '+p.s+'.':p.s+' em estoque.'}</small><div class="rw"><span class="pr">${R(p.p)}</span><button class="btn" data-act="add" data-id="${p.id}" ${p.s<1?'disabled':''}>${I('cart','s')} Adicionar ao carrinho</button></div><a class="btn wa" data-wa="${p.id}" href="#" target="_blank" rel="noopener">${I('wa','')}Pedir pelo WhatsApp</a><small class="mu">Deslize para os lados para ver outras opções.</small>`;if(!rm())$('#dpb').animate([{opacity:.2,transform:'translateX(14px)'},{opacity:1,transform:'none'}],{duration:300,easing:'ease-out'});if(!$('#dp').open)$('#dp').showModal()}
else if(a==='vw'){$('#sml').hidden=true;$('#sk').setAttribute('aria-expanded',false);go(t.dataset.v)}
else if(a==='ac')acct();
});
$('#q').oninput=$('#st').onchange=()=>{$('#gr').classList.add('go');shop()};
// Limpa o formulário de produto e retorna ao modo de cadastro.
const fr0=()=>{const f=$('#af');f.reset();delete f.dataset.id;$('#sv').textContent='Adicionar';$('#fh').textContent='Novo item';$('#cx').hidden=true};$('#cx').onclick=fr0;
// Preenche o formulário de configurações com os dados atuais da loja.
function sset(){const E=$('#sf').elements;E.nm.value=ST.nm;E.wa.value=ST.wa;E.ad.value=ST.ad;E.hr.value=ST.hr}
// Aplica o nome, contato, endereço, horário e regras de frete da loja.
function brand(){document.title=ST.nm;$('.logo').textContent=ST.nm;$('#fr').textContent='Entrega a combinar pelo WhatsApp. O valor varia conforme o bairro.';setm(mode);
$('#ft').innerHTML='<b>'+esc(ST.nm)+'</b>'+(ST.ad?'<br><a href="https://maps.app.goo.gl/NaYr1wmbS8GGxL8X9" target="_blank" rel="noopener noreferrer" style="text-decoration:underline">'+I('pin','s')+' '+esc(ST.ad)+'</a>':'')+(ST.hr?'<br>'+I('clock','s')+' '+esc(ST.hr):'')+(ST.wa?'<br><a href="https://wa.me/'+esc(ST.wa)+'">'+I('wa','s')+' Fale conosco no WhatsApp</a>':'')+'<br><small>Pedidos registrados na loja. Pagamento e entrega são combinados com o atendimento.</small>'}
/* conta */
// Abre a janela de login/cadastro.
const openLogin=()=>{$('#er').textContent='';$('#dl').showModal()};
$('#ub').onclick=()=>{if(me)acct();else openLogin()};
// Configura o modo de autenticação (cadastro, login, telefone ou Google simulado).
function setm(m){mode=m;otp='';$$('[data-md]').forEach(x=>x.hidden=!x.dataset.md.split(' ').includes(m));$('#cdw').hidden=true;$('#sb').textContent={up:'Criar conta',in:'Entrar',ph:'Receber código',gg:'Continuar'}[m];$('#dlt').textContent={up:'Criar conta',in:'Entrar',ph:'Entrar com o WhatsApp',gg:'Entrar com o Google'}[m];$('#tg').textContent=m==='up'?'Já tenho conta':m==='in'?'Criar conta':'Voltar';$('#er').textContent=$('#inf').textContent='';$('#pw').autocomplete=m==='up'?'new-password':'current-password'}
$('#tg').onclick=()=>setm(mode==='in'?'up':mode==='up'?'in':'up');$('#gb').onclick=()=>setm('gg');$('#wb').onclick=()=>setm('ph');
// Valida os dados e conclui o fluxo local de login/cadastro.
/* carrinho e recado */
$('#cb').onclick=()=>{cartUI();$('#dc').showModal()};
// Valida e finaliza o pedido, atualiza estoque e prepara o envio pelo WhatsApp.
$('#np').oninput=()=>{NT.p=$('#np').value;nrender()};$('#nt').oninput=()=>{NT.m=$('#nt').value;nrender()};$('#nd').oninput=()=>{NT.d=$('#nd').value;nrender()};$$('[data-k]').forEach(b=>b.onclick=()=>{NT.k=b.dataset.k;nrender()});
const nbar=()=>{const b=$('#nb');b.hidden=!note.trim();b.textContent='Seu recado para '+NT.p.trim()+' está pronto. Escolha as flores para completar o pedido.'};
const dtx=()=>NT.k==='a'?'Anônimo':NT.k==='m'?'Um admirador':(NT.d.trim()||me?.n||'—');
// Atualiza a prévia do cartão e guarda o recado no armazenamento local.
function nrender(){note=NT.p.trim()||NT.m.trim()?'Para: '+(NT.p.trim()||'—')+'\n'+NT.m.trim()+'\nDe: '+dtx():'';sv('nt',NT);$('#cd').innerHTML=note?'<span>Para: <b>'+esc(NT.p.trim()||'—')+'</b></span><span>'+esc(NT.m.trim())+'</span><span class="de">De: '+esc(dtx())+'</span>':'Seu recado aparece aqui.';$('#nd').hidden=NT.k!=='n';$$('[data-k]').forEach(b=>b.setAttribute('aria-pressed',b.dataset.k===NT.k));if(!$('#nb').hidden)nbar()}
$('#sl').onclick=ev=>{if(!NT.p.trim()){toast('Informe o nome de quem vai receber o recado.');$('#np').focus();return}if(!NT.m.trim()){toast('Escreva o recado antes de guardar.');$('#nt').focus();return}const r=ev.currentTarget.getBoundingClientRect();burst(r.left+r.width/2,r.top);nbar();toast('Recado guardado. Agora escolha as flores.');setTimeout(()=>{$('#loja').scrollIntoView();$('#lt').focus({preventScroll:true})},900)};
// Monta o endereço oficial wa.me a partir do número cadastrado.
const wl=m=>{let n=ST.wa.replace(/^0+/,'');if(n.length<=11)n='55'+n;return'https://wa.me/'+n+'?text='+encodeURIComponent(m)};
// Cria a mensagem de WhatsApp com produtos, quantidades, frete e recado.
const wcart=()=>{const c=calc(),ad=$('#ad').value.trim(),dt=$('#dt').value;return'Olá, '+ST.nm+'! Quero fazer um pedido:\n'+c.it.map(x=>'• '+x.q+'× '+x.p.n+' ('+R(x.p.p*x.q)+')').join('\n')+'\nSubtotal — entrega não incluída: '+R(c.sub)+'\nEntrega: a combinar conforme o bairro. Por favor, confirme o frete e o valor final.'+(note.trim()?'\nRecado no cartão: '+note.trim():'')+(ad?'\nEntrega em: '+ad:'')+(dt?'\nData: '+dt.split('-').reverse().join('/'):'')+'\nNome: '+$('#gn2').value.trim()+'\nTelefone: '+$('#gt').value.trim()};
// Valida se há WhatsApp cadastrado e configura o link de contato.
const wgo=(ev,el,m,box)=>{if(!ST.wa){ev.preventDefault();const t='O WhatsApp da loja ainda não foi cadastrado.'+(me?.r==='admin'?' Cadastre em Dados da loja, no painel.':'');if(box)box.textContent=t;else toast(t);return}el.href=wl(m())};
$('#wf').onclick=ev=>wgo(ev,ev.currentTarget,()=>'Olá, '+ST.nm+'! Vim pelo site e gostaria de ajuda.');
$('#cw').onclick=ev=>{$('#ce').textContent='';if(!calc().it.length){ev.preventDefault();$('#ce').textContent='Seu carrinho está vazio.';return}wgo(ev,ev.currentTarget,wcart,$('#ce'))};
document.addEventListener('click',ev=>{const a=ev.target.closest('[data-wa]');if(!a)return;const p=prods.find(x=>x.id==a.dataset.wa);wgo(ev,a,()=>'Olá, '+ST.nm+'! Tenho interesse em: '+p.n+' ('+R(p.p)+'). Está disponível?')});
$('#ow').onclick=ev=>wgo(ev,ev.currentTarget,()=>$('#ow').dataset.m,$('#owm'));$('#cbb').onclick=()=>$('#cb').click();
/* animações de clique */
// Verifica se o visitante prefere reduzir ou desativar animações.
const rm=()=>matchMedia('(prefers-reduced-motion:reduce)').matches;
document.addEventListener('pointerdown',ev=>{const b=ev.target.closest('.btn,.chip,.tile,.ib,.qt button,.fv,.wf,.rb');if(!b||b.disabled||rm())return;const r=b.getBoundingClientRect(),z=Math.max(r.width,r.height)*2,e=document.createElement('i');e.className='rp';e.style.cssText='width:'+z+'px;height:'+z+'px;left:'+(ev.clientX-r.left-z/2)+'px;top:'+(ev.clientY-r.top-z/2)+'px';b.appendChild(e);e.onanimationend=()=>e.remove()});
// Anima visualmente o produto em direção ao ícone do carrinho.
function fly(el){if(rm())return;const a=el.getBoundingClientRect(),c=$('#cb').getBoundingClientRect(),f=document.createElement('div'),dx=c.left+c.width/2-a.left-a.width/2,dy=c.top+c.height/2-a.top-a.height/2;f.textContent='🌸';f.setAttribute('aria-hidden','true');f.style.cssText='position:fixed;z-index:60;pointer-events:none;font-size:28px;left:'+(a.left+a.width/2-14)+'px;top:'+(a.top+a.height/2-14)+'px';document.body.appendChild(f);
f.animate([{transform:'translate(0,0) scale(1) rotate(0)',opacity:1},{transform:'translate('+dx*.5+'px,'+(dy*.5-70)+'px) scale(1.3) rotate(120deg)',opacity:1,offset:.45},{transform:'translate('+dx+'px,'+dy+'px) scale(.3) rotate(300deg)',opacity:.2}],{duration:800,easing:'ease-in-out'}).onfinish=()=>{f.remove();const b=$('#cb');b.classList.remove('bump');void b.offsetWidth;b.classList.add('bump')}}
addEventListener('scroll',()=>{const h=document.documentElement,m=h.scrollHeight-h.clientHeight;$('#sp').style.transform='scaleX('+(m>0?scrollY/m:0)+')';$('header').classList.toggle('sc',scrollY>8)},{passive:true});
// Revela elementos quando entram na área visível da tela.
function rvl(){if(rm())return;const io=new IntersectionObserver(es=>es.forEach(e=>{if(e.isIntersecting){const el=e.target;el.classList.add('in');io.unobserve(el);el.addEventListener('transitionend',()=>{el.classList.remove('rv','in');el.style.transitionDelay=''},{once:true})}}),{threshold:.15});$$('.tile,.steps li,#loja h2,#como h2,.hero h1').forEach((el,i)=>{el.classList.add('rv');el.style.transitionDelay=(i%6)*70+'ms';io.observe(el)})}
let tl;if(matchMedia('(hover:hover)').matches&&!rm()){document.addEventListener('pointermove',ev=>{const t=ev.target.closest?.('.tile');if(tl&&tl!==t)tl.style.transform=tl.style.transition='';tl=t;if(!t)return;const r=t.getBoundingClientRect(),x=(ev.clientX-r.left)/r.width-.5,y=(ev.clientY-r.top)/r.height-.5;t.style.transition='transform .1s';t.style.transform='perspective(600px) rotateX('+-y*10+'deg) rotateY('+x*12+'deg) translateY(-3px)'});
$('.hero').addEventListener('pointermove',ev=>{const r=ev.currentTarget.getBoundingClientRect();$('#hv').style.transform='translate('+((ev.clientX-r.left)/r.width-.5)*16+'px,'+((ev.clientY-r.top)/r.height-.5)*12+'px)'});$('.hero').addEventListener('pointerleave',()=>$('#hv').style.transform='')}
let sw=null,sd=null,sx=null;
document.addEventListener('pointerdown',ev=>{const l=ev.target.closest('.li'),h=ev.target.closest('.dh');if(l&&!ev.target.closest('button'))sw={l,x:ev.clientX,id:l.dataset.id};if(h&&!ev.target.closest('button'))sd={d:h.closest('dialog'),y:ev.clientY};if(ev.target.closest('#dp')&&!ev.target.closest('button,a,.dh'))sx={x:ev.clientX,y:ev.clientY}});
document.addEventListener('pointermove',ev=>{if(sw){const d=Math.min(0,ev.clientX-sw.x);sw.l.style.transition='none';sw.l.style.transform='translateX('+d+'px)';sw.l.style.opacity=1+d/240}if(sd){sd.d.style.transition='none';sd.d.style.transform='translateY('+Math.max(0,ev.clientY-sd.y)+'px)'}});
document.addEventListener('pointerup',ev=>{if(sw){const{l,id}=sw,d=ev.clientX-sw.x;sw=null;l.style.transition='';if(d<-90){l.style.transform='translateX(-110%)';l.style.opacity=0;setTimeout(()=>{delete cart[id];sv('c',cart);cartUI();hdr()},250)}else{l.style.transform='';l.style.opacity=''}}
if(sd){const{d}=sd,dy=ev.clientY-sd.y;sd=null;d.style.transition='';d.style.transform='';if(dy>110)d.close()}
if(sx){const dx=ev.clientX-sx.x,dy=Math.abs(ev.clientY-sx.y);sx=null;if(Math.abs(dx)>70&&dy<50){const cur=+$('#dpb [data-act=add]')?.dataset.id,n=LST[LST.indexOf(cur)+(dx<0?1:-1)];if(n)$('[data-act=view][data-id="'+n+'"]')?.click()}}});
let pendC='',asked=0,k2='n';
function n2(){$('#nd2').hidden=k2!=='n';$$('[data-k2]').forEach(b=>b.setAttribute('aria-pressed',b.dataset.k2===k2))}
$$('[data-k2]').forEach(b=>b.onclick=()=>{k2=b.dataset.k2;n2()});
// Abre o formulário alternativo de recado quando necessário.
function askNote(){if(note.trim()||asked)return;asked=1;$('#np2').value=NT.p;$('#nt2').value=NT.m;$('#nd2').value=NT.d||me?.n||'';k2=NT.k;n2();$('#nr').textContent='';$('#dn').showModal()}
$('#fn').onsubmit=ev=>{ev.preventDefault();const p=$('#np2').value.trim(),m=$('#nt2').value.trim();if(!p){$('#nr').textContent='Informe o nome de quem vai receber.';return}if(!m){$('#nr').textContent='Escreva o recado.';return}NT={p,m,d:$('#nd2').value,k:k2};$('#np').value=p;$('#nt').value=m;$('#nd').value=NT.d;nrender();$('#dn').close();toast('Recado guardado no seu pedido.')};
// Cria uma nova categoria no painel administrativo.
$$('dialog').forEach(d=>d.addEventListener('click',e=>{if(e.target===d)d.close()}));
// Inicialização: monta a ilustração, carrega configurações e desenha a página.
/* início */
$('#hv').innerHTML=art('#f4a9b8','#c2456b');


$('#np').value=NT.p;$('#nt').value=NT.m;$('#nd').value=NT.d;nrender();$('#fxb').setAttribute('aria-pressed',on);
cats();
$('#dt').min=new Date(Date.now()-new Date().getTimezoneOffset()*6e4).toISOString().slice(0,10);sset();brand();if(note.trim())nbar();hdr();shop();admin();run();rvl();

(function(){
    const root = document.querySelector('.carousel');
    if(!root) return;
    const viewport = root.querySelector('.car__viewport');
    const track = root.querySelector('.car__track');
    const prevBtn = root.querySelector('.car__btn--prev');
    const nextBtn = root.querySelector('.car__btn--next');
    const dotsWrap = root.querySelector('.car__dots');
    const autoplay = root.dataset.autoplay === 'true';
    const intervalMs = Number(root.dataset.interval)||5000;
  
    // Základní slidy (3)
    const baseSlides = Array.from(track.children);
  
    // Vytvoř tečky podle počtu slidů
    baseSlides.forEach((_,i)=>{
      const b=document.createElement('button'); b.className='dot'; b.setAttribute('aria-label',`Snímek ${i+1}`); dotsWrap.appendChild(b);
    });
    const dots = Array.from(dotsWrap.children);
  
    // Nekonečný efekt: přidej klony na začátek/konec
    const first = baseSlides[0].cloneNode(true);
    const last = baseSlides[baseSlides.length-1].cloneNode(true);
    track.insertBefore(last, baseSlides[0]);
    track.appendChild(first);
    const slidesAll = Array.from(track.children); // [last, ...baseSlides, first]
  
    let index = 1; // ukazuje na první reálný slide
    let timer; let animating=false;
  
    function slideWidth(){ return viewport.clientWidth; }
    function at(n){ return Math.max(0, Math.min(n, slidesAll.length-1)); }
  
    function goto(n, {animate=true}={}){
      if(animating) return; animating=true;
      const w = slideWidth();
      track.style.transition = animate ? 'transform .45s ease' : 'none';
      track.style.transform = `translateX(${-w*n}px)`;
      track.addEventListener('transitionend', function handler(){
        track.removeEventListener('transitionend', handler);
        // přeskoky pro nekonečný loop
        if(n===slidesAll.length-1){ n=1; track.style.transition='none'; track.style.transform=`translateX(${-w*n}px)`; }
        if(n===0){ n=slidesAll.length-2; track.style.transition='none'; track.style.transform=`translateX(${-w*n}px)`; }
        index=n; updateDots(); animating=false;
      });
    }
    function realIndex(){ let n=index-1; if(n<0) n=baseSlides.length-1; if(n>=baseSlides.length) n=0; return n; }
    function updateDots(){ dots.forEach((d,i)=> d.classList.toggle('is-active', i===realIndex())); }
    function next(){ goto(index+1); }
    function prev(){ goto(index-1); }
  
    // Layout init
    function layout(){ const w=slideWidth(); track.style.transition='none'; track.style.transform=`translateX(${-w*index}px)`; }
    window.addEventListener('resize', layout);
  
    // Ovladani
    nextBtn.addEventListener('click', next);
    prevBtn.addEventListener('click', prev);
    dots.forEach((d,i)=> d.addEventListener('click', ()=> goto(i+1)));
  
    // Klávesnice
    root.addEventListener('keydown', (e)=>{ if(e.key==='ArrowRight') next(); if(e.key==='ArrowLeft') prev(); });
    root.setAttribute('tabindex','0');
  
    // Autoplay
    function start(){ if(!autoplay) return; stop(); timer=setInterval(next, intervalMs); }
    function stop(){ if(timer) clearInterval(timer); }
    root.addEventListener('mouseenter', stop);
    root.addEventListener('mouseleave', start);
  
    // Swipe (touch)
    let startX=0, dx=0, touching=false;
    viewport.addEventListener('touchstart', (e)=>{ touching=true; startX=e.touches[0].clientX; dx=0; stop(); }, {passive:true});
    viewport.addEventListener('touchmove', (e)=>{ if(!touching) return; dx=e.touches[0].clientX-startX; const w=slideWidth(); track.style.transition='none'; track.style.transform=`translateX(${(-w*index)+dx}px)`; }, {passive:true});
    viewport.addEventListener('touchend', ()=>{ if(!touching) return; touching=false; const w=slideWidth(); if(Math.abs(dx)>w*0.2){ dx>0? prev(): next(); } else { goto(index, {animate:true}); } start(); }, {passive:true});
  
    // Start!
    layout(); updateDots(); start();
  })();
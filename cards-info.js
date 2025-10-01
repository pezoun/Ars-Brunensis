(function(){
    const grid = document.querySelector('.cards');
    if(!grid) return;
  
    function closeAll(){
      grid.querySelectorAll('.card.is-open').forEach(c=>{
        c.classList.remove('is-open');
        c.querySelectorAll('.card__panel').forEach(p=>p.remove());
      });
    }
  
    function buildPanel(){
      const panel = document.createElement('div');
      panel.className = 'card__panel';
      panel.innerHTML = `
        <button class="panel__close" aria-label="Zavřít">&times;</button>
        <h3>Detail koncertu</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, dolore? Quae delectus ratione cumque laudantium. (Nahraď vlastním textem.)</p>`;
      panel.querySelector('.panel__close').addEventListener('click', e=>{ e.stopPropagation(); closeAll(); });
      return panel;
    }
  
    // Otevření panelu na klik na .chip (info)
    grid.addEventListener('click', (e)=>{
      const btn = e.target.closest('.chip');
      if(!btn) return;
      e.preventDefault();
      e.stopPropagation();
      const card = btn.closest('.card');
      if(!card) return;
      const isOpen = card.classList.contains('is-open');
      closeAll();
      if(!isOpen){ card.appendChild(buildPanel()); card.classList.add('is-open'); }
    });
  
    // Klik KDEKOLI mimo otevřenou kartu zavře zpět do původního stavu
    document.addEventListener('click', (e)=>{
      const openCard = document.querySelector('.card.is-open');
      if(!openCard) return;
      if(!e.target.closest('.card.is-open')) closeAll();
    });
  
    // ESC zavření
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeAll(); });
  })();
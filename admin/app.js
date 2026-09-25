document.addEventListener('click', function(e){
  const del=e.target.closest('[data-confirm-delete]');
  if(del && !confirm('¿Estás seguro de eliminar este registro?')) e.preventDefault();
});

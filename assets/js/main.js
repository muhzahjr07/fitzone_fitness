// Simple confirm() helper for deletes
document.addEventListener('click', (e) => {
  const el = e.target.closest('[data-confirm]');
  if (el && !confirm(el.getAttribute('data-confirm'))) {
    e.preventDefault();
  }
});

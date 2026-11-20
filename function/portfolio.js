const toggle = document.querySelector('.menu-toggle');
const navigation = document.getElementById('navigation');
toggle.addEventListener('click', () => {
  const open = toggle.getAttribute('aria-expanded') === 'true';
  toggle.setAttribute('aria-expanded', String(!open));
  navigation.classList.toggle('is-open', !open);
});
navigation.addEventListener('click', event => {
  if (event.target.closest('a')) {
    toggle.setAttribute('aria-expanded', 'false');
    navigation.classList.remove('is-open');
  }
});
document.addEventListener('keydown', event => {
  if (event.key === 'Escape' && navigation.classList.contains('is-open')) {
    toggle.setAttribute('aria-expanded', 'false');
    navigation.classList.remove('is-open');
    toggle.focus();
  }
});
document.getElementById('year').textContent = new Date().getFullYear();
document.querySelectorAll('video, audio').forEach(media => media.addEventListener('play', () => {
  document.querySelectorAll('video, audio').forEach(other => { if (other !== media) other.pause(); });
}));

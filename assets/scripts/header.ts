import './searchBar';

const burger = document.querySelector('.burger');
const menu = document.querySelector('.menu-xs');
const content = document.querySelector('.app-content');

burger.addEventListener('click', (event: MouseEvent) => {
    menu.classList.toggle('hidden');
});

content.addEventListener('click', (event: MouseEvent) => {
    if(!menu.classList.contains('hidden')) {
        menu.classList.add('hidden');
    }
});


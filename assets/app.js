/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

let dropdownBtns = document.getElementsByClassName('dropdown-menu-activator');

document.getElementsByClassName('dropdown-menu-activator');

for(let dropdownBtn of dropdownBtns) {
  dropdownBtn.addEventListener('click', (element) => {
    let dropdownContent = document.getElementById("addAddressForm")
    display(dropdownContent);
  })
}
const display = (dropdownContent) => {
  if (dropdownContent.classList.contains('show')) 
    dropdownContent.classList.remove('show');
  else
    dropdownContent.classList.toggle("show");
}
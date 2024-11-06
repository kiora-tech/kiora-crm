/**
* Template Name: NiceAdmin
* Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
* Updated: Apr 20 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

import * as bootstrap from 'bootstrap';
import * as simpleDatatables from 'simple-datatables';

function init() {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    if (all) {
      select(el, all).forEach(e => {
        e.removeEventListener(type, listener)
        e.addEventListener(type, listener)
      })
    } else {
      select(el, all).removeEventListener(type, listener)
      select(el, all).addEventListener(type, listener)
    }
  }

  /**
   * Easy on scroll event listener 
   */
  const onscroll = (el, listener) => {
    el.removeEventListener('scroll', listener)
    el.addEventListener('scroll', listener)
  }
/**
 * Search bar toggle
 */
if (select('.search-bar-toggle')) {
  function toggle(e) {
    e.stopPropagation(); // Arrête la propagation du clique qui provoque des erreurs
    e.preventDefault(); // Bonus
    const searchBar = select('.search-bar');
    searchBar.classList.toggle('search-bar-show');
  }

  const searchBarToggle = select('.search-bar-toggle');
  
  if (!searchBarToggle.dataset.listenerAdded) {
    searchBarToggle.addEventListener('click', toggle);
    searchBarToggle.dataset.listenerAdded = 'true'; // Ajout d'un attribut pour s'assurer de l'état du composent
  }
}
  /**
   * Sidebar toggle
   */
  if (select('.toggle-sidebar-btn')) {
    function toggle(e) {
      select('body').classList.toggle('toggle-sidebar');
  
      updateSidebarLinks();
    }
  
    function updateSidebarLinks() {
      if (window.innerWidth > 1199) {
      const links = document.querySelectorAll('ul.sidebar-nav li.nav-item a.nav-link');
      const filteredLinks = Array.from(links);
      const sidebarNav = select('ul.sidebar-nav');
    
      filteredLinks.forEach(link => {
        if (!link.dataset.originalText) {
          link.dataset.originalText = link.textContent.trim();
        }
    
        const iconHTML = link.querySelector('i');
    
        if (select('body').classList.contains('toggle-sidebar')) {
          link.innerHTML = `${iconHTML.outerHTML} `;
          link.dataset.hidden = "true";
    
          sidebarNav.style.width = '50px';
          sidebarNav.style.position = 'absolute';
          sidebarNav.style.right = '0';
        } else {
          link.innerHTML = `${iconHTML.outerHTML} ${link.dataset.originalText}`;
          link.dataset.hidden = "false";
    
          sidebarNav.style.width = '';
          sidebarNav.style.position = '';
          sidebarNav.style.right = '';
        }
      }
      )};
    }

    function checkDeviceWidth() {
        select('body').classList.remove('toggle-sidebar');
        updateSidebarLinks();
    }
  
    select('.toggle-sidebar-btn').replaceWith(select('.toggle-sidebar-btn').cloneNode(true));
    on('click', '.toggle-sidebar-btn', toggle);
  

    const sidebarLinks = document.querySelectorAll('ul.sidebar-nav li.nav-item a.nav-link');
    sidebarLinks.forEach(link => {
      link.addEventListener('click', () => {

        const href = link.getAttribute('href');
        if (href) {
          window.location.href = href; 
        }
      });
    });
  

    window.addEventListener('load', () => {
      checkDeviceWidth(); // First check
    });
  
    window.addEventListener('resize', checkDeviceWidth);
  }
  

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('turbo:load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select('#header')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
      } else {
        selectHeader.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('turbo:load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('turbo:load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /**
   * Initiate tooltips
   */
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })


  /**
   * Initiate Bootstrap validation check
   */
  var needsValidation = document.querySelectorAll('.needs-validation')

  Array.prototype.slice.call(needsValidation)
    .forEach(function(form) {
      function submit(event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }
      form.removeEventListener('submit', submit, false)
      form.addEventListener('submit', submit, false)
    })

  /**
   * Initiate Datatables
   */
  const datatables = select('.datatable', true);
  datatables.forEach((datatable, index) => {
  

  
    // Extract all rows and ensure they have at least one cell
    const rows = Array.from(datatable.querySelectorAll('tr')).map((row, rowIndex) => {
      const cellData = Array.from(row.children).map(cell => cell.innerText);
      console.log(`Row ${rowIndex} data:`, cellData); // Log the data for each row
      return cellData;
    }).filter(row => row.length > 0); // Filter out empty rows
  

  
    if (rows.length === 0 || rows[0].length === 0) {

      return; 
    }
  
    const headings = rows[0]; 
    console.log('Headings:', headings); 
  
    const data = rows.slice(1).filter((row, rowIndex) => {
      const isValid = row.length === headings.length; 

      return isValid; 
    });
  

  
    // Add a default row if no valid data exists
    if (data.length === 0) {
      data.push(new Array(headings.length).fill('No data available'));
      console.warn('No valid data rows found. Adding default row.');
    }
  
    try {
      new simpleDatatables.DataTable(datatable, {
        data: {
          headings: headings, 
          data: data 
        },
        perPageSelect: [5, 10, 15, ["All", -1]],
        columns: [{
            select: 2,
            sortSequence: ["desc", "asc"]
          },
          {
            select: 3,
            sortSequence: ["desc"]
          },
          {
            select: 4,
            cellClass: "green",
            headerClass: "red"
          }
        ]
      });
      console.log(`DataTable initialized successfully for datatable index: ${index}`);
    } catch (error) {
      console.error(`Error initializing DataTable for datatable index: ${index}`, error);
    }
  });
  
}

window.addEventListener("turbo:load", init);
init();

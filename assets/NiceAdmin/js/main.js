/**
* Template Name: NiceAdmin
* Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
* Updated: Apr 20 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/


import 'quill';
import 'tinymce';
import * as bootstrap from 'bootstrap';
import * as simpleDatatables from 'simple-datatables';
import 'echarts';

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
   * Initiate quill editors
   */
  if (select('.quill-editor-default')) {
    new Quill('.quill-editor-default', {
      theme: 'snow'
    });
  }

  if (select('.quill-editor-bubble')) {
    new Quill('.quill-editor-bubble', {
      theme: 'bubble'
    });
  }

  if (select('.quill-editor-full')) {
    new Quill(".quill-editor-full", {
      modules: {
        toolbar: [
          [{
            font: []
          }, {
            size: []
          }],
          ["bold", "italic", "underline", "strike"],
          [{
              color: []
            },
            {
              background: []
            }
          ],
          [{
              script: "super"
            },
            {
              script: "sub"
            }
          ],
          [{
              list: "ordered"
            },
            {
              list: "bullet"
            },
            {
              indent: "-1"
            },
            {
              indent: "+1"
            }
          ],
          ["direction", {
            align: []
          }],
          ["link", "image", "video"],
          ["clean"]
        ]
      },
      theme: "snow"
    });
  }

  /**
   * Initiate TinyMCE Editor
   */

  const useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const isSmallScreen = window.matchMedia('(max-width: 1023.5px)').matches;

  tinymce.init({
    selector: 'textarea.tinymce-editor',
    plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
    editimage_cors_hosts: ['picsum.photos'],
    menubar: 'file edit view insert format tools table help',
    toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
    autosave_ask_before_unload: true,
    autosave_interval: '30s',
    autosave_prefix: '{path}{query}-{id}-',
    autosave_restore_when_empty: false,
    autosave_retention: '2m',
    image_advtab: true,
    link_list: [{
        title: 'My page 1',
        value: 'https://www.tiny.cloud'
      },
      {
        title: 'My page 2',
        value: 'http://www.moxiecode.com'
      }
    ],
    image_list: [{
        title: 'My page 1',
        value: 'https://www.tiny.cloud'
      },
      {
        title: 'My page 2',
        value: 'http://www.moxiecode.com'
      }
    ],
    image_class_list: [{
        title: 'None',
        value: ''
      },
      {
        title: 'Some class',
        value: 'class-name'
      }
    ],
    importcss_append: true,
    file_picker_callback: (callback, value, meta) => {
      /* Provide file and text for the link dialog */
      if (meta.filetype === 'file') {
        callback('https://www.google.com/logos/google.jpg', {
          text: 'My text'
        });
      }

      /* Provide image and alt text for the image dialog */
      if (meta.filetype === 'image') {
        callback('https://www.google.com/logos/google.jpg', {
          alt: 'My alt text'
        });
      }

      /* Provide alternative source and posted for the media dialog */
      if (meta.filetype === 'media') {
        callback('movie.mp4', {
          source2: 'alt.ogg',
          poster: 'https://www.google.com/logos/google.jpg'
        });
      }
    },
    height: 600,
    image_caption: true,
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    noneditable_class: 'mceNonEditable',
    toolbar_mode: 'sliding',
    contextmenu: 'link image table',
    skin: useDarkMode ? 'oxide-dark' : 'oxide',
    content_css: useDarkMode ? 'dark' : 'default',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
  });

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
  
  

  
  /**
   * Autoresize echart charts
   */
  const mainContainer = select('#main');
  if (mainContainer) {
    setTimeout(() => {
      new ResizeObserver(function() {
        select('.echart', true).forEach(getEchart => {
          echarts.getInstanceByDom(getEchart).resize();
        })
      }).observe(mainContainer);
    }, 200);
  }

}

window.addEventListener("turbo:load", init);
init();

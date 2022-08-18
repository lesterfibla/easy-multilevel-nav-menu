// get all menus
var menu = document.querySelectorAll('.emnm_menu');
// count all menus
var menu_length = menu.length;

for (var i = 0; i < menu_length; i++) {
	var menu_el = menu[i];
	var breakpoint = menu_el.getAttribute('data-breakpoint');
	var menu_id = menu_el.getAttribute('id');
	var related_menu_btn_el = document.getElementById(menu_id + '_btn')
	var large_device = window.matchMedia('(min-width: ' + breakpoint + 'px)');
	function handle_device_change(e) {
		if (e.matches) {
			menu_el.classList.add('emnm_desktop')
			related_menu_btn_el.classList.add('emnm_desktop')
		} else {
			menu_el.classList.remove('emnm_desktop')
			related_menu_btn_el.classList.remove('emnm_desktop')
		}
	}
	// listen for device viewport change
	large_device.addListener(handle_device_change);
	// run it initially
	handle_device_change(large_device);
}


// get all menu btns
var menu_btn = document.querySelectorAll('.emnm_menu_btn');
// count all menu btns
var menu_btn_length = menu_btn.length;

for (var i = 0; i < menu_btn_length; i++) {
	var menu_btn_el = menu_btn[i];
	var related_menu_id = menu_btn_el.getAttribute('data-menu');
	var related_menu_el = document.getElementById(related_menu_id);
	menu_btn_el.addEventListener('click', function () {
		related_menu_el.classList.add('emnm_menu_mobile_open');
	})
}


// get all link btns
var link_btn = document.querySelectorAll('.emnm_link_btn');
// count all link btns
var link_btn_length = link_btn.length;

for (var i = 0; i < link_btn_length; i++) {
	link_btn[i].addEventListener('click', function (e) {
		var el = e.target || e.srcElement;
		var parentListTag = el.parentElement.parentElement;
		if (parentListTag.classList.contains('emnm_submenu_open')) {
			parentListTag.classList.remove('emnm_submenu_open')
		} else {
			parentListTag.classList.add('emnm_submenu_open')
		}
	})
}


var menu_container = document.querySelectorAll('.emnm_menu_container')
for (var i = 0; i < menu_container.length; i++) {
	menu_container[i].addEventListener('click', function (e) {
		var el = e.target || e.srcElement;
		var parentListTag = el.parentElement;
		parentListTag.classList.remove('emnm_menu_mobile_open');
	})
}

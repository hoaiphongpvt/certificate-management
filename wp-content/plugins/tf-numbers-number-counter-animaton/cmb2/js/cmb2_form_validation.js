/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */


jQuery(document).ready(function($) {
  $('form#post').submit(function() {
    if($('input#_tf_stat_0__tf_title').val() === '') {
      alert('Please enter a value for the textbox');
      return false;
    }
  });
});



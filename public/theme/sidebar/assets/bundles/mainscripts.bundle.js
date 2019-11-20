$(function(){"use strict";setTimeout(function(){$(".page-loader-wrapper").fadeOut()},50);if($('[data-toggle="tooltip"]').tooltip(),$('[data-toggle="popover"]').popover({html:!0}),$('[data-toggle="card-remove"]').on("click",function(a){return $(this).closest("div.card").remove(),a.preventDefault(),!1}),$('[data-toggle="card-collapse"]').on("click",function(a){return $(this).closest("div.card").toggleClass("card-collapsed"),a.preventDefault(),!1}),$('[data-toggle="card-fullscreen"]').on("click",function(a){return $(this).closest("div.card").toggleClass("card-fullscreen").removeClass("card-collapsed"),a.preventDefault(),!1}),$("[data-sparkline]").length){var a=function(a,b,c){a.sparkline(b,{type:a.attr("data-sparkline-type"),height:"100%",barColor:c.color,lineColor:c.color,fillColor:"transparent",spotColor:c.color,spotRadius:0,lineWidth:2,highlightColor:hexToRgba(c.color,.6),highlightLineColor:"#666",defaultPixelsPerValue:5})};require(["sparkline"],function(){$("[data-sparkline]").each(function(){var b=$(this);a(b,JSON.parse(b.attr("data-sparkline")),{color:b.attr("data-sparkline-color")})})})}$(".chart-circle").length&&$(".chart-circle").each(function(){var a=$(this);a.circleProgress({fill:{color:buzzer.colors[a.attr("data-color")]||buzzer.colors.blue},size:a.height(),startAngle:-Math.PI/4*2,emptyFill:"#F4F4F4",lineCap:"round"})}),$("a.mega_menu").on("click",function(){$(".mega_menubar").toggleClass("open vivify pullDown")}),$("a.settingbar").on("click",function(){$(".right_sidebar").toggleClass("open")}),$(".choose-skin li").on("click",function(){var a=$("body"),b=$(this),c=$(".choose-skin li.active").data("theme");$(".choose-skin li").removeClass("active"),a.removeClass("theme-"+c),b.addClass("active"),a.addClass("theme-"+b.data("theme"))}),$(".accordion2 > .accordion-item.is-active").children(".accordion-panel").slideDown(),$(".accordion2 > .accordion-item").on("click",function(){$(this).siblings(".accordion-item").removeClass("is-active").children(".accordion-panel").slideUp(),$(this).toggleClass("is-active").children(".accordion-panel").slideToggle("ease-out")}),$(document).ready(function(){$(".star").on("click",function(){$(this).toggleClass("star-checked")}),$(".ckbox label").on("click",function(){$(this).parents("tr").toggleClass("selected")}),$(".btn-filter").on("click",function(){var a=$(this).data("target");"all"!=a?($(".table tr").css("display","none"),$('.table tr[data-status="'+a+'"]').fadeIn("slow")):$(".table tr").css("display","none").fadeIn("slow")})}),$(".bh_visitors").sparkline("html",{type:"bar",height:"16px",barColor:"#467fcf",barWidth:5}),$(".bh_visits").sparkline("html",{type:"bar",height:"16px",barColor:"#5eba00",barWidth:5}),$(".bh_chats").sparkline("html",{type:"bar",height:"16px",barColor:"#f1c40f",barWidth:5})}),$(document).ready(function(){$("#main-menu").metisMenu(),$(".btn-toggle-fullwidth").on("click",function(){$("body").hasClass("layout-fullwidth")?($("body").removeClass("layout-fullwidth"),$(this).find(".fa").toggleClass("fa-arrow-left fa-arrow-right")):($("body").addClass("layout-fullwidth"),$(this).find(".fa").toggleClass("fa-arrow-left fa-arrow-right"))}),$(".header-toggler").on("click",function(){$("body").toggleClass("offcanvas-active")}),$("#main-content").on("click",function(){$("body").removeClass("offcanvas-active")}),$(window).on("load",function(){$("#main-content").height()<$("#left-sidebar").height()&&$("#main-content").css("min-height",$("#left-sidebar").innerHeight()-$("footer").innerHeight())})}),window.buzzer={colors:{blue:"#467fcf","blue-darkest":"#0e1929","blue-darker":"#1c3353","blue-dark":"#3866a6","blue-light":"#7ea5dd","blue-lighter":"#c8d9f1","blue-lightest":"#edf2fa",azure:"#45aaf2","azure-darkest":"#0e2230","azure-darker":"#1c4461","azure-dark":"#3788c2","azure-light":"#7dc4f6","azure-lighter":"#c7e6fb","azure-lightest":"#ecf7fe",indigo:"#6574cd","indigo-darkest":"#141729","indigo-darker":"#282e52","indigo-dark":"#515da4","indigo-light":"#939edc","indigo-lighter":"#d1d5f0","indigo-lightest":"#f0f1fa",purple:"#a55eea","purple-darkest":"#21132f","purple-darker":"#42265e","purple-dark":"#844bbb","purple-light":"#c08ef0","purple-lighter":"#e4cff9","purple-lightest":"#f6effd",pink:"#f66d9b","pink-darkest":"#31161f","pink-darker":"#622c3e","pink-dark":"#c5577c","pink-light":"#f999b9","pink-lighter":"#fcd3e1","pink-lightest":"#fef0f5",red:"#e74c3c","red-darkest":"#2e0f0c","red-darker":"#5c1e18","red-dark":"#b93d30","red-light":"#ee8277","red-lighter":"#f8c9c5","red-lightest":"#fdedec",orange:"#fd9644","orange-darkest":"#331e0e","orange-darker":"#653c1b","orange-dark":"#ca7836","orange-light":"#feb67c","orange-lighter":"#fee0c7","orange-lightest":"#fff5ec",yellow:"#f1c40f","yellow-darkest":"#302703","yellow-darker":"#604e06","yellow-dark":"#c19d0c","yellow-light":"#f5d657","yellow-lighter":"#fbedb7","yellow-lightest":"#fef9e7",lime:"#7bd235","lime-darkest":"#192a0b","lime-darker":"#315415","lime-dark":"#62a82a","lime-light":"#a3e072","lime-lighter":"#d7f2c2","lime-lightest":"#f2fbeb",green:"#5eba00","green-darkest":"#132500","green-darker":"#264a00","green-dark":"#4b9500","green-light":"#8ecf4d","green-lighter":"#cfeab3","green-lightest":"#eff8e6",teal:"#2bcbba","teal-darkest":"#092925","teal-darker":"#11514a","teal-dark":"#22a295","teal-light":"#6bdbcf","teal-lighter":"#bfefea","teal-lightest":"#eafaf8",cyan:"#17a2b8","cyan-darkest":"#052025","cyan-darker":"#09414a","cyan-dark":"#128293","cyan-light":"#5dbecd","cyan-lighter":"#b9e3ea","cyan-lightest":"#e8f6f8",gray:"#868e96","gray-darkest":"#1b1c1e","gray-darker":"#36393c","gray-dark":"#6b7278","gray-light":"#aab0b6","gray-lighter":"#dbdde0","gray-lightest":"#f3f4f5","gray-dark":"#343a40","gray-dark-darkest":"#0a0c0d","gray-dark-darker":"#15171a","gray-dark-dark":"#2a2e33","gray-dark-light":"#717579","gray-dark-lighter":"#c2c4c6","gray-dark-lightest":"#ebebec"}};
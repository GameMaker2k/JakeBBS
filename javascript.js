/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 JakeBBS - http://google.com/search?q=JakeBBS+Inc.&btnI=I%27m+Feeling+Lucky
    Copyright 2004-2009 JakeBBS Inc. - http://google.com/search?q=JakeBBS+Inc.&btnI=I%27m+Feeling+Lucky

    $FileInfo: javascript.js - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
function getid(id) {
var itm;
itm = document.getElementById(id);
return itm; }

function toggletag(id) {
var itm;
itm = document.getElementById(id);
if (itm.style.display == "none") {
itm.style.display = ""; }
else {
itm.style.display = "none"; } }

function bgchange(id,color) {
var itm;
itm = document.getElementById(id);
itm.style.backgroundColor = ''+color+''; }

function innerchange(tag,text1,text2) {
var usrname;
usrname = document.getElementsByTagName(tag);
for (var i = 0; i < usrname.length; i++) {
if(usrname[i].innerHTML==text1) {
usrname[i].innerHTML = text2; } } }

function addsmiley(id,code) {
var itm;
itm = document.getElementById(id);
var pretext = itm.value;
itm.value = pretext + code; }
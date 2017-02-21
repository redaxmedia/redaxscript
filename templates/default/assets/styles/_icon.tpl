/**
* @tableofcontents
*
* 1. icon
*
* @since 3.0.0
*
* @package Redaxscript
* @author Henry Ruhs
*/

/* @section 1. icon */

@font-face
{
	font-family: var(--rs-font-icon);
	src: url('../../../../templates/default/dist/fonts/icon.woff2') format('woff2'), url('../../../../templates/default/dist/fonts/icon.woff') format('woff');
}
<% for (var i in glyphs)
{
%>
%rs-icon-<%=glyphs[i] %>
{
	content: '\<%= codepoints[i] %>';
	font-family: var(--rs-font-icon);
	font-style: normal;
	font-weight: normal;
}
<%
}
%>
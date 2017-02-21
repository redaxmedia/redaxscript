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
	font-family: var(--rs-admin-font-icon);
	src: url('../../../../templates/admin/dist/fonts/icon.woff2') format('woff2'), url('../../../../templates/admin/dist/fonts/icon.woff') format('woff');
}
<% for (var i in glyphs)
{
%>
%rs-admin-icon-<%=glyphs[i] %>
{
	content: '\<%= codepoints[i] %>';
	font-family: var(--rs-admin-font-icon);
	font-style: normal;
	font-weight: normal;
}
<%
}
%>
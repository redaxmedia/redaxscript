/* @section 1. icon */

@font-face
{
	font-family: var(--rs-font-icon);
	src: url('templates/default/assets/fonts/icon.woff2') format('woff2'), url('templates/default/assets/fonts/icon.woff') format('woff');
}
<% for (var i in glyphs)
{
%>
%rs-icon-<%=glyphs[i] %>:before
{
	content: '\<%= codepoints[i] %>';
	font-family: var(--rs-font-icon);
}
<%
}
%>
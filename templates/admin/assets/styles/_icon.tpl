/* @section 1. icon */

@font-face
{
	font-family: var(--rs-admin-font-icon);
	src: url('../fonts/icon.woff2') format('woff2'), url('../fonts/icon.woff') format('woff');
}
<% for (var i in glyphs)
{
%>
%rs-admin-icon-<%=glyphs[i] %>
{
	content: '\<%= codepoints[i] %>';
	font-family: var(--rs-admin-font-icon);
	font-weight: normal;
}
<%
}
%>
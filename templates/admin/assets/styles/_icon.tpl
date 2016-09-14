/* @section 1. icon */

@font-face
{
	font-family: var(--rs-admin-font-icon);
	src: url('../../assets/fonts/icon.woff2') format('woff2'), url('../../assets/fonts/icon.woff') format('woff');
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
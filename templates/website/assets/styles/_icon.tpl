/* @section 1. icon */

@font-face
{
	font-family: var(--rs-website-font-icon);
	src: url('../../../../templates/website/dist/fonts/icon.woff2') format('woff2'), url('../../../../templates/website/dist/fonts/icon.woff') format('woff');
}
<% for (var i in glyphs)
{
%>
%rs-website-icon-<%=glyphs[i] %>
{
	content: '\<%= codepoints[i] %>';
	font-family: var(--rs-website-font-icon);
	font-weight: normal;
}
<%
}
%>
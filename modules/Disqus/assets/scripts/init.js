/**
 * @tableofcontents
 *
 * 1. disqus
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @author Henry Ruhs
 */

/* @section 1. disqus */

window.disqus_identifier = rs.registry.fullRoute;
window.disqus_title = document.title;
window.disqus_url = rs.baseURL + rs.registry.rewriteRoute + rs.registry.fullRoute;
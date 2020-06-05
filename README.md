# ss-template-cache-helper
Some simple helpers for template cache (partial cache) in order to simplify the setup of partial cache. A good starting point for most websites.


## Features

* Use a simple cacheblock for making sure most common data models are taken into considaration 
* 2 diffent cacheKeys, one sitewide (defaultSitewideCacheKey) - not page/url specific, and one pagewide (defaultPageCacheKey) for each unique page
* Extension hooks so you can add your own cache invalidation rules for each page type.

### Example 

For Sitewide caching, for instance in the `<footer>` part of the site
```html
<% cached "page-footer", $TemplateSitewideCacheKey %>
    <footer>
        <p>$SiteConfig.FooterText</p>
    </footer>
<% end_cached % 
```

Page specific page cache, in this instance we add it to the page.ss template.
```html
<% cached "page", $TemplatePageCacheKey %>
    <% include SideBar %>
    <div class="main">
        <h1>$Title</h1>
        <div class="typography">
            $Content    
        </div>
    </div>
<% end_cached %>
```

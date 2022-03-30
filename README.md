# Plugin for SEO markup and generation Sitemap.xml and Robots.txt

The plugin adds functionality to Standard Meta tags and generates Sitemap and Robots files.

> It supports **CMS Pages**, **Static Pages**, **Blog posts** and **Builder's pages records**.

### Functionality:

- Generation of meta tags (title, description, canonical)
- Close any page from indexing using robots meta tags
- Add the company name at the beginning or at the end of all headings
- File generation sitemap.xml and robots.txt
- Supports **CMS Pages**, **Static Pages** and **Blog posts**
- Add a dynamic list of pages to a file Sitemap.xml. For example, pages created using the **Builder plugin**
- Generation of Open Graph micro markup
- SEO meta tag fields support twig syntax
- Insert third-party Meta tags or a third-party script into \<head>

Currently supported Open Graph tags:
- `og:title` - defaults to _page meta\_title | page title_
- `og:description` - defaults to _page meta\_description | site description_ in the Settings page
- `og:image` - defaults to  _page image|site image_ in Settings page - Open Graph tab
- `og:type` - defaults to "website"
- `og:locale` - defaults to "en_US"
- `twitter:title` - from `og:title`
- `twitter:description` - from `og:description`
- `twitter:image` - from `og:image`

---

## Start using the plugin

You need to connect the components and insert them in the right place in the code and enable the necessary options in the plugin settings

```bash
{% component 'META' %}
```

If you have a custom model that you want to generate the links from, add the full class name of your model in the "Sitemap" tab of the **CMS page**.
If the page has the `blogPost` component, you don't need to set the Model class.

**Important!**: Make sure that all the necessary parameters are enabled on the settings page.

---

> **The plugin has not been tested on OctoberCMS v2.x**

> If you find any errors or typos in the code or interface of the plugin, please let me know

---
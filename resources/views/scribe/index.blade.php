<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Control 77 API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://proyecties.test";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.0.1.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.0.1.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-user">
                                <a href="#endpoints-GETapi-user">GET api/user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-guards">
                                <a href="#endpoints-GETapi-guards">GET api/guards</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-guards">
                                <a href="#endpoints-POSTapi-guards">POST api/guards</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-guards--id-">
                                <a href="#endpoints-GETapi-guards--id-">GET api/guards/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-PUTapi-guards--id-">
                                <a href="#endpoints-PUTapi-guards--id-">PUT api/guards/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-DELETEapi-guards--id-">
                                <a href="#endpoints-DELETEapi-guards--id-">DELETE api/guards/{id}</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-guards-assign-zone">
                                <a href="#endpoints-POSTapi-guards-assign-zone">POST api/guards/assign-zone</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: April 15, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://proyecties.test</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {YOUR_AUTH_KEY}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>You can retrieve your token by visiting your dashboard and clicking <b>Generate API token</b>.</p>

        <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-user">GET api/user</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-user">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://proyecties.test/api/user" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://proyecties.test/api/user"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-user">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-user" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-user"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-user"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-user" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-user">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-user" data-method="GET"
      data-path="api/user"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-user', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-user"
                    onclick="tryItOut('GETapi-user');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-user"
                    onclick="cancelTryOut('GETapi-user');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-user"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/user</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-user"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-user"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-guards">GET api/guards</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-guards">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://proyecties.test/api/guards" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://proyecties.test/api/guards"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-guards">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Leire Ramos&quot;,
        &quot;dni&quot;: &quot;54972529G&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;D&ntilde;a Rosa Peralta Tercero&quot;,
        &quot;dni&quot;: &quot;19774833I&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 3,
        &quot;name&quot;: &quot;Ainara Jaimes&quot;,
        &quot;dni&quot;: &quot;67436694A&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 4,
        &quot;name&quot;: &quot;Isabel Abrego&quot;,
        &quot;dni&quot;: &quot;45832057H&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 5,
        &quot;name&quot;: &quot;D. Joel Valencia Segundo&quot;,
        &quot;dni&quot;: &quot;52380062B&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 6,
        &quot;name&quot;: &quot;Elena Hernando&quot;,
        &quot;dni&quot;: &quot;06967148D&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 7,
        &quot;name&quot;: &quot;Natalia Lozada&quot;,
        &quot;dni&quot;: &quot;96017269I&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 8,
        &quot;name&quot;: &quot;Andrea Galarza&quot;,
        &quot;dni&quot;: &quot;13259000S&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 9,
        &quot;name&quot;: &quot;Helena Hern&aacute;ndes&quot;,
        &quot;dni&quot;: &quot;26397722P&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 10,
        &quot;name&quot;: &quot;Diana Borrego&quot;,
        &quot;dni&quot;: &quot;53129693G&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 11,
        &quot;name&quot;: &quot;Sof&iacute;a Orosco Segundo&quot;,
        &quot;dni&quot;: &quot;26053591Y&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 12,
        &quot;name&quot;: &quot;Sra. Nayara Verdugo&quot;,
        &quot;dni&quot;: &quot;68625731M&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 13,
        &quot;name&quot;: &quot;Sara Dom&iacute;nguez&quot;,
        &quot;dni&quot;: &quot;43149669Y&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 14,
        &quot;name&quot;: &quot;Asier Campos&quot;,
        &quot;dni&quot;: &quot;60909270X&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 15,
        &quot;name&quot;: &quot;C&eacute;sar Carrasquillo&quot;,
        &quot;dni&quot;: &quot;54296777J&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 16,
        &quot;name&quot;: &quot;Mar&iacute;a Pilar Henr&iacute;quez&quot;,
        &quot;dni&quot;: &quot;14768627N&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 17,
        &quot;name&quot;: &quot;Ing. &Oacute;scar Cabello&quot;,
        &quot;dni&quot;: &quot;99539177R&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 18,
        &quot;name&quot;: &quot;Sra. Eva Medina&quot;,
        &quot;dni&quot;: &quot;84631185M&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 19,
        &quot;name&quot;: &quot;Beatriz Abreu&quot;,
        &quot;dni&quot;: &quot;50385463P&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 20,
        &quot;name&quot;: &quot;D&ntilde;a Ver&oacute;nica Carrero Hijo&quot;,
        &quot;dni&quot;: &quot;79349528H&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 21,
        &quot;name&quot;: &quot;Rodrigo Santill&aacute;n&quot;,
        &quot;dni&quot;: &quot;15008089V&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 22,
        &quot;name&quot;: &quot;Lidia Montero&quot;,
        &quot;dni&quot;: &quot;40445483T&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 23,
        &quot;name&quot;: &quot;Candela Duarte&quot;,
        &quot;dni&quot;: &quot;05093624M&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 24,
        &quot;name&quot;: &quot;Rayan Pi&ntilde;eiro&quot;,
        &quot;dni&quot;: &quot;14708711I&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 25,
        &quot;name&quot;: &quot;Jan Sol&iacute;s&quot;,
        &quot;dni&quot;: &quot;36384387N&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 26,
        &quot;name&quot;: &quot;Mar&iacute;a Gimeno Segundo&quot;,
        &quot;dni&quot;: &quot;68931007X&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 27,
        &quot;name&quot;: &quot;Izan Rom&aacute;n&quot;,
        &quot;dni&quot;: &quot;80080195Y&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 28,
        &quot;name&quot;: &quot;Nerea Pati&ntilde;o&quot;,
        &quot;dni&quot;: &quot;95812095G&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 29,
        &quot;name&quot;: &quot;Ing. Claudia Mel&eacute;ndez Tercero&quot;,
        &quot;dni&quot;: &quot;07555128G&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 30,
        &quot;name&quot;: &quot;Marc Monta&ntilde;ez&quot;,
        &quot;dni&quot;: &quot;12226401C&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 31,
        &quot;name&quot;: &quot;Ander Salinas&quot;,
        &quot;dni&quot;: &quot;04811580I&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 32,
        &quot;name&quot;: &quot;Manuela Alanis&quot;,
        &quot;dni&quot;: &quot;21317635C&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 33,
        &quot;name&quot;: &quot;Ariadna Villalobos&quot;,
        &quot;dni&quot;: &quot;91171014F&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 34,
        &quot;name&quot;: &quot;Zoe Alaniz&quot;,
        &quot;dni&quot;: &quot;71785593D&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 35,
        &quot;name&quot;: &quot;Mateo Alc&aacute;ntar&quot;,
        &quot;dni&quot;: &quot;23845686B&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 36,
        &quot;name&quot;: &quot;Srta. Zoe L&oacute;pez Hijo&quot;,
        &quot;dni&quot;: &quot;15351648R&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 37,
        &quot;name&quot;: &quot;Luc&iacute;a Armenta&quot;,
        &quot;dni&quot;: &quot;61411632M&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 38,
        &quot;name&quot;: &quot;Clara Barrios&quot;,
        &quot;dni&quot;: &quot;59156866L&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 39,
        &quot;name&quot;: &quot;Srta. Ainhoa Trujillo&quot;,
        &quot;dni&quot;: &quot;78297836I&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 40,
        &quot;name&quot;: &quot;Alba Garibay&quot;,
        &quot;dni&quot;: &quot;65386285I&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 41,
        &quot;name&quot;: &quot;Nadia Laureano&quot;,
        &quot;dni&quot;: &quot;60356672H&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 42,
        &quot;name&quot;: &quot;Francisca Dele&oacute;n&quot;,
        &quot;dni&quot;: &quot;65375255H&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
    },
    {
        &quot;id&quot;: 43,
        &quot;name&quot;: &quot;Dr. Pau Garrido Tercero&quot;,
        &quot;dni&quot;: &quot;18474694J&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 44,
        &quot;name&quot;: &quot;Julia Lim&oacute;n&quot;,
        &quot;dni&quot;: &quot;73220893C&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 45,
        &quot;name&quot;: &quot;Lorena Aguado&quot;,
        &quot;dni&quot;: &quot;00025007G&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 46,
        &quot;name&quot;: &quot;Mar Montemayor&quot;,
        &quot;dni&quot;: &quot;03199377C&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 47,
        &quot;name&quot;: &quot;Yolanda Valverde&quot;,
        &quot;dni&quot;: &quot;77161949T&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 48,
        &quot;name&quot;: &quot;Natalia Segovia Segundo&quot;,
        &quot;dni&quot;: &quot;37169977N&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 49,
        &quot;name&quot;: &quot;Lic. Alex Carbajal&quot;,
        &quot;dni&quot;: &quot;54494643A&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 50,
        &quot;name&quot;: &quot;Manuela Sanz&quot;,
        &quot;dni&quot;: &quot;14857907G&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 51,
        &quot;name&quot;: &quot;&Aacute;ngel Gayt&aacute;n Segundo&quot;,
        &quot;dni&quot;: &quot;55533931R&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 52,
        &quot;name&quot;: &quot;Jordi Armas&quot;,
        &quot;dni&quot;: &quot;42425443W&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 53,
        &quot;name&quot;: &quot;Lic. Silvia Guevara Segundo&quot;,
        &quot;dni&quot;: &quot;18474214C&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 54,
        &quot;name&quot;: &quot;Gael Sanabria&quot;,
        &quot;dni&quot;: &quot;05197908X&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 55,
        &quot;name&quot;: &quot;Biel Varela&quot;,
        &quot;dni&quot;: &quot;41785644U&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 56,
        &quot;name&quot;: &quot;Sr. Oriol Granados Hijo&quot;,
        &quot;dni&quot;: &quot;04226597N&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 57,
        &quot;name&quot;: &quot;Cristina Miranda Segundo&quot;,
        &quot;dni&quot;: &quot;28181158B&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 58,
        &quot;name&quot;: &quot;Dr. Noelia Font Tercero&quot;,
        &quot;dni&quot;: &quot;84741068F&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 59,
        &quot;name&quot;: &quot;C&eacute;sar Cano&quot;,
        &quot;dni&quot;: &quot;24992016O&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    },
    {
        &quot;id&quot;: 60,
        &quot;name&quot;: &quot;D&ntilde;a &Uacute;rsula T&oacute;rrez Hijo&quot;,
        &quot;dni&quot;: &quot;88627659F&quot;,
        &quot;created_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;,
        &quot;updated_at&quot;: &quot;2025-04-15T17:45:52.000000Z&quot;
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-guards" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-guards"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-guards"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-guards" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-guards">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-guards" data-method="GET"
      data-path="api/guards"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-guards', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-guards"
                    onclick="tryItOut('GETapi-guards');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-guards"
                    onclick="cancelTryOut('GETapi-guards');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-guards"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/guards</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-guards"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-guards"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-guards"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-guards">POST api/guards</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-guards">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://proyecties.test/api/guards" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"dni\": \"n\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://proyecties.test/api/guards"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "dni": "n"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-guards">
</span>
<span id="execution-results-POSTapi-guards" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-guards"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-guards"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-guards" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-guards">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-guards" data-method="POST"
      data-path="api/guards"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-guards', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-guards"
                    onclick="tryItOut('POSTapi-guards');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-guards"
                    onclick="cancelTryOut('POSTapi-guards');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-guards"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/guards</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-guards"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-guards"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-guards"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-guards"
               value="b"
               data-component="body">
    <br>
<p>El campo value no debe ser mayor que 255 caracteres. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dni</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="dni"                data-endpoint="POSTapi-guards"
               value="n"
               data-component="body">
    <br>
<p>El campo value no debe ser mayor que 255 caracteres. Example: <code>n</code></p>
        </div>
        </form>

                    <h2 id="endpoints-GETapi-guards--id-">GET api/guards/{id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-guards--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://proyecties.test/api/guards/1" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://proyecties.test/api/guards/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-guards--id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;id&quot;: 1,
    &quot;name&quot;: &quot;Leire Ramos&quot;,
    &quot;dni&quot;: &quot;54972529G&quot;,
    &quot;created_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;,
    &quot;updated_at&quot;: &quot;2025-04-15T17:45:51.000000Z&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-guards--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-guards--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-guards--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-guards--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-guards--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-guards--id-" data-method="GET"
      data-path="api/guards/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-guards--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-guards--id-"
                    onclick="tryItOut('GETapi-guards--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-guards--id-"
                    onclick="cancelTryOut('GETapi-guards--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-guards--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/guards/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-guards--id-"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-guards--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-guards--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-guards--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the guard. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-PUTapi-guards--id-">PUT api/guards/{id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-guards--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://proyecties.test/api/guards/1" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"b\",
    \"dni\": \"n\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://proyecties.test/api/guards/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "b",
    "dni": "n"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PUTapi-guards--id-">
</span>
<span id="execution-results-PUTapi-guards--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-guards--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-guards--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-guards--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-guards--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-guards--id-" data-method="PUT"
      data-path="api/guards/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-guards--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-guards--id-"
                    onclick="tryItOut('PUTapi-guards--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-guards--id-"
                    onclick="cancelTryOut('PUTapi-guards--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-guards--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/guards/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/guards/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-guards--id-"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-guards--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-guards--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-guards--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the guard. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-guards--id-"
               value="b"
               data-component="body">
    <br>
<p>El campo value no debe ser mayor que 255 caracteres. Example: <code>b</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>dni</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="dni"                data-endpoint="PUTapi-guards--id-"
               value="n"
               data-component="body">
    <br>
<p>El campo value no debe ser mayor que 255 caracteres. Example: <code>n</code></p>
        </div>
        </form>

                    <h2 id="endpoints-DELETEapi-guards--id-">DELETE api/guards/{id}</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-DELETEapi-guards--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://proyecties.test/api/guards/1" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://proyecties.test/api/guards/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-DELETEapi-guards--id-">
</span>
<span id="execution-results-DELETEapi-guards--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-guards--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-guards--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-guards--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-guards--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-guards--id-" data-method="DELETE"
      data-path="api/guards/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-guards--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-guards--id-"
                    onclick="tryItOut('DELETEapi-guards--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-guards--id-"
                    onclick="cancelTryOut('DELETEapi-guards--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-guards--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/guards/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-guards--id-"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-guards--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-guards--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="DELETEapi-guards--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the guard. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="endpoints-POSTapi-guards-assign-zone">POST api/guards/assign-zone</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-guards-assign-zone">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://proyecties.test/api/guards/assign-zone" \
    --header "Authorization: Bearer {YOUR_AUTH_KEY}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"guard_id\": 16,
    \"zone_id\": 16,
    \"schedule\": \"20:00 - 10:00\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://proyecties.test/api/guards/assign-zone"
);

const headers = {
    "Authorization": "Bearer {YOUR_AUTH_KEY}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "guard_id": 16,
    "zone_id": 16,
    "schedule": "20:00 - 10:00"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-guards-assign-zone">
</span>
<span id="execution-results-POSTapi-guards-assign-zone" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-guards-assign-zone"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-guards-assign-zone"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-guards-assign-zone" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-guards-assign-zone">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-guards-assign-zone" data-method="POST"
      data-path="api/guards/assign-zone"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-guards-assign-zone', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-guards-assign-zone"
                    onclick="tryItOut('POSTapi-guards-assign-zone');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-guards-assign-zone"
                    onclick="cancelTryOut('POSTapi-guards-assign-zone');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-guards-assign-zone"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/guards/assign-zone</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-guards-assign-zone"
               value="Bearer {YOUR_AUTH_KEY}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_AUTH_KEY}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-guards-assign-zone"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-guards-assign-zone"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>guard_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="guard_id"                data-endpoint="POSTapi-guards-assign-zone"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the guards table. Example: <code>16</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>zone_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="zone_id"                data-endpoint="POSTapi-guards-assign-zone"
               value="16"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the zones table. Example: <code>16</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>schedule</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="schedule"                data-endpoint="POSTapi-guards-assign-zone"
               value="20:00 - 10:00"
               data-component="body">
    <br>
<p>Schedule format 24h. Example: <code>20:00 - 10:00</code></p>
        </div>
        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>

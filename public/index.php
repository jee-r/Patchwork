<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last.fm top albums patchwork generator</title>
    <meta charset="utf-8" />
    <meta name="description" content="A tool that generates a patchwork, an image, based on the covers of your Last.fm top albums. It's simple, free, and it works." />
    <meta name="keywords" content="lastfm top albums generator, last.fm top albums generator, lastfm top albums, last.fm top albums, lastfm, last.fm, top albums" />
    <link rel="icon" type="image/svg+xml" href="/assets/favicon.svg">
    <link rel="icon" type="image/png" href="/assets/favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/apple-touch-icon.png">
    <link href="/styles/pico.min.css" rel="stylesheet" type="text/css" />
    <link href="/styles/main.css" rel="stylesheet" type="text/css" />
    <script src="/scripts/main.js"></script>
</head>

<body>
    <nav>
        <ul>
            <li class="logo">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                    <g id="SvgjsG1004" transform="translate(.252 .236)">
                        <rect id="SvgjsRect1003" width="8.019" height="8.035" x="0" y="0" rx="0" ry=".772" style="display:inline;fill:#e41c61;fill-opacity:1;stroke-width:.75" />
                        <rect id="SvgjsRect1002" width="8.019" height="8.035" x="0" y="8.635" rx="0" ry=".772" style="display:inline;fill:#191919;fill-opacity:1;stroke-width:.75" />
                        <rect id="SvgjsRect1001" width="8.019" height="8.035" x="8.619" y="0" rx="0" ry=".772" style="display:inline;fill:#191919;fill-opacity:1;stroke-width:.75" />
                        <rect id="SvgjsRect1000" width="8.019" height="8.035" x="8.619" y="8.635" rx="0" ry=".772" style="display:inline;fill:#e41c61;stroke-width:.75" />
                    </g>
                </svg>
            </li>
            <li><strong>Top Album <span class="lov3">Patchwork</span></strong></li>
        </ul>
    </nav>
    <main class="container">
        <article>
            <form action="patchwork.php" method="GET" onsubmit="submitForm(event)">
                <label for="username">
                    LastFM Username
                    <input type="text" id="username" name="username" placeholder="username" required>
                </label>

                <fieldset class="grid">
                    <legend>Period</legend>
                    <div>
                        <label for="overall">
                            <input type="radio" id="overall" name="period" value="overall" checked>
                            Overall
                        </label>
                        <label for="7day">
                            <input type="radio" id="7day" name="period" value="7day">
                            7 days
                        </label>
                        <label for="1month">
                            <input type="radio" id="1month" name="period" value="1month">
                            1 Month
                        </label>
                    </div>
                    <div>
                        <label for="3month">
                            <input type="radio" id="3month" name="period" value="3month">
                            3 Months
                        </label>
                        <label for="6month">
                            <input type="radio" id="6month" name="period" value="6month">
                            6 Months
                        </label>
                        <label for="1year">
                            <input type="radio" id="1year" name="period" value="12month">
                            1 Year
                        </label>
                    </div>
                </fieldset>

                <fieldset class="grid">
                    <div>
                        <label for="cols">Nr. of rows
                            <output id="rowsOutput">3</output>
                        </label>
                        <input type="range" min="1" max="20" value="3" id="rows" name="rows" oninput="rowsOutput.value = rows.value">
                        </label>
                    </div>
                    <div>
                        <label for="cols">Nr. of columns
                            <output id="colsOutput">3</output>
                        </label>
                        <input type="range" min="1" max="20" value="3" id="cols" name="cols" oninput="colsOutput.value = cols.value">
                        </label>
                    </div>
                </fieldset>
                <fieldset class="">
                    <label for="imageSize">Images size in pixel</label>
                    <input type="text" value="150" name="imageSize" id="imagesSize" />
                    <label for="noborder">
                        <input type="checkbox" name="noborder" id="noborder" />
                        No border
                    </label>
                </fieldset>
                <button id="submitbtn" aria-invalid="true" type="submit">Generate</button>
            </form>
            <input id="messagebox" class="hidden" type="text" readonly>

            <!-- generated Patchwork goes here  -->

            <div class="hidden" id="resultcontainer">
                <h2 id="patchworkTitle"></h2>
                <div class="patchwork">
                    <img src="" id="patchworkImg" width="" height="" alt="Patchwork">
                    <a id="downloadbtn" role="button" class="primary" href="" download>Download</a>
                </div>
                <div class="">
                    <div class="field-title">Dynamyc Image link :</div>
                    <div class="img-link">
                        <a id="patchworkDynLink" href="" target="_blank"></a>
                    </div>
                    <div role="button" class="outline contrast" onclick="copyToClipboard(event, 'patchworkDynLink')">Copy</div>
                </div>
                <div>
                    <div class="field-title">Static Image link :</div>
                    <div class="img-link">
                        <a id="patchworkStaticLink" href="" target="_blank"></a>
                    </div>
                    <div role="button" class="outline contrast" onclick="copyToClipboard(event, 'patchworkStaticLink')">Copy</div>
                </div>
            </div>
        </article>
    </main>
    <footer>
        <div>made with <span class="lov3">
                <3< /span>
        </div>
        <div>by <a href="https://artz.dev" target="_blank" rel="noopener noreferrer">Jee</a></div>
        <a class="outline contrast" href="https://github.com/jee-r/Patchwork" role="">
            Fork Me
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                <path fill="currentColor" d="M2.6 10.59L8.38 4.8l1.69 1.7c-.24.85.15 1.78.93 2.23v5.54c-.6.34-1 .99-1 1.73a2 2 0 0 0 2 2a2 2 0 0 0 2-2c0-.74-.4-1.39-1-1.73V9.41l2.07 2.09c-.07.15-.07.32-.07.5a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2c-.18 0-.35 0-.5.07L13.93 7.5a1.98 1.98 0 0 0-1.15-2.34c-.43-.16-.88-.2-1.28-.09L9.8 3.38l.79-.78c.78-.79 2.04-.79 2.82 0l7.99 7.99c.79.78.79 2.04 0 2.82l-7.99 7.99c-.78.79-2.04.79-2.82 0L2.6 13.41c-.79-.78-.79-2.04 0-2.82Z" />
            </svg>
        </a>
    </footer>
</body>

</html>
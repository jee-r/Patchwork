<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last.fm top albums patchwork generator</title>
    <meta charset="utf-8" />
    <meta name="description" content="A tool that generates a patchwork, an image, based on the covers of your Last.fm top albums. It's simple, free, and it works." />
    <meta name="keywords" content="lastfm top albums generator, last.fm top albums generator, lastfm top albums, last.fm top albums, lastfm, last.fm, top albums" />
    <link href="styles/main.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/vendor/picocss/pico/css/pico.min.css">
    <script src="/scripts/main.js"></script>
</head>
<body>
    <nav class="container-fluid">
        <ul>
            <li><strong>Top Album Patchwork</strong></li>
        </ul>
        <ul>
            <li>
                <a class="contrast" href="https://github.com" role="button">
                    Fork Me
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M2.6 10.59L8.38 4.8l1.69 1.7c-.24.85.15 1.78.93 2.23v5.54c-.6.34-1 .99-1 1.73a2 2 0 0 0 2 2a2 2 0 0 0 2-2c0-.74-.4-1.39-1-1.73V9.41l2.07 2.09c-.07.15-.07.32-.07.5a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2c-.18 0-.35 0-.5.07L13.93 7.5a1.98 1.98 0 0 0-1.15-2.34c-.43-.16-.88-.2-1.28-.09L9.8 3.38l.79-.78c.78-.79 2.04-.79 2.82 0l7.99 7.99c.79.78.79 2.04 0 2.82l-7.99 7.99c-.78.79-2.04.79-2.82 0L2.6 13.41c-.79-.78-.79-2.04 0-2.82Z" />
                    </svg>
                </a>
            </li>
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
                        <label for="3months">
                            <input type="radio" id="3months" name="period" value="3months">
                            3 Months
                        </label>
                        <label for="6months">
                            <input type="radio" id="6months" name="period" value="6months">
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
                <button id="submitbtn" type="submit">Generate</button>
            </form>
        </article>

        <article class="hidden" id="resultcontainer">
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

            <div class="patchwork">
                <img src="" id="patchworkImg" width="" height="" alt="Patchwork">
            </div>
        </article>
    </main>
</body>

</html>
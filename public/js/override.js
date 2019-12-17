setTimeout(function () {
    if(document.querySelector('.description--support')){
        document.querySelector('.description--support').innerHTML += '<iframe src="https://ghbtns.com/github-btn.html?user=pgrimaud&repo=horaires-ratp-api&type=star&count=true" frameborder="0" scrolling="0" width="170px" height="20px" align="center"></iframe></p>'
    }
    if(document.querySelector('.description--source')){
        document.querySelector('.description--source').innerHTML += '<iframe src="https://ghbtns.com/github-btn.html?user=pgrimaud&repo=ratp-api-rest&type=star&count=true" frameborder="0" scrolling="0" width="170px" height="20px" align="center"></iframe></p>'
    }

    let sponsorScript = document.createElement('script');
    sponsorScript.setAttribute('src','https://buttons.github.io/buttons.js');
    document.body.appendChild(sponsorScript);
}, 300);

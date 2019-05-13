setTimeout(function () {
    if(document.querySelector('.schemes')){
        document.querySelector('.schemes').innerHTML = '<p>This project is not affiliated with or endorsed by the RATP.</p>' +
          '<p>Support is available <a target="_blank" href="https://github.com/pgrimaud/horaires-ratp-api">here</a>. ' +
          '<iframe src="https://ghbtns.com/github-btn.html?user=pgrimaud&repo=horaires-ratp-api&type=star&count=true" frameborder="0" scrolling="0" width="170px" height="20px" align="center"></iframe></p>' +
          '<p>Source code is available <a target="_blank" href="https://github.com/pgrimaud/ratp-api-rest">here</a>. ' +
          '<iframe src="https://ghbtns.com/github-btn.html?user=pgrimaud&repo=ratp-api-rest&type=star&count=true" frameborder="0" scrolling="0" width="170px" height="20px" align="center"></iframe></p>' +
          '<p>Official RATP SOAP API status is available on this <a href="https://p.datadoghq.com/sb/b933ad64d-3d03339edc63b8ba89556675024d64fd" target="_blank">dashboard</a>.</p>' +
          '<u><b>BEWARE</b></u> : Work in progress, use at your own risk.'
    }
}, 300);

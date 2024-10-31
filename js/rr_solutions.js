var rr_rating = document.getElementById('rr_rating');
if (rr_rating) {
    console.log(rr_rating);
    var rr_app = document.createElement('script');
    rr_app.type = 'text/javascript';
    rr_app.async = true;
    rr_app.src = '//solutions.runrepeat.com/rating/js/app.js?sc=1&t=' + rr_rating.getAttribute("data-name") + '&p=' + encodeURIComponent(document.URL);
    document.getElementsByTagName('body')[0].appendChild(rr_app)
}
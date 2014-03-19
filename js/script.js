$(document).ready(function() {
    /**
        FLIPBOX
    **/
    var id, oldId, plot, rating, genre, year, json, imgId, flipboxId;
    $(document).on("click", ".info-button", function clickAction(e) {
        flipboxId = e.target.id + "flipbox";
        oldId = id;
        id = "#" + flipboxId;
        url = "../data/top100/" + e.target.id + ".json";
        $.ajax({
            url: url,
            async: false,
            datatype: 'json',
            success: function(data) {
                plot = data["Plot"];
                rating = data["imdbRating"];
                genre = data["Genre"];
                year = data["Year"];
            }
        });
        if (oldId != id) {
            $(id).flippy({
                color_target: "#000",
                direction: "right",
                duration: "350",
                verso: "<div class=\"backside\"><p>Rating: " + rating + "/10</p><p>Year: " + year + "</p><p>Genre: " + genre + "</p><p>" + plot + "</p></div>",
            })
            $(oldId).flippyReverse();
            oldId = null;
        }
        if (oldId == id) {
            $(id).flippyReverse();
            id = null;
        }
    });

    /**
        Fancybox fyrir trailer takka.
    **/
    $("a.video-layer").click(function() {
        $.fancybox({
            'padding': 0,
            'autoScale': false,
            'width': 680,
            'height': 495,
            'href': this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
            'type': 'swf',
            'swf': {
                'wmode': 'transparent',
                'allowfullscreen': 'true'
            }
        });
        return false;
    });

    /**
        Fancybox fyrir login takka
    **/
    $("a#login-button").click(function() {
        $.fancybox({
            'autoDimensions': false,
            'fitToView': false,
            'autoSize': false,
            'width': '400',
            'height': '250',
            'type': 'iframe',
            'href': this.href,
            afterClose: function() {
                parent.location.reload(true);
            }
        });
        return false;
    });

    /**
        Fancybox fyrir create new user takka
    **/
    $("a#newuser-button").click(function() {
        $.fancybox({
            'autoDimensions': false,
            'fitToView': false,
            'autoSize': false,
            'width': '400',
            'height': '270',
            'type': 'iframe',
            'href': this.href,
            afterClose: function() {
                parent.location.reload(true);
            }
        });
        return false;
    });

    /**
        Fancybox fyrir unregister takka
    **/
    $("a#unregister-button").click(function() {
        $.fancybox({
            'autoDimensions': false,
            'fitToView': false,
            'autoSize': false,
            'width': '350',
            'height': '250',
            'type': 'iframe',
            'href': this.href,
            afterClose: function() {
                parent.location.reload(true);
            }
        });
        return false;
    });

    /**
        Fancybox fyrir update account takka
    **/
    $("a#update-button").click(function() {
        $.fancybox({
            'autoDimensions': false,
            'fitToView': false,
            'autoSize': false,
            'width': '400',
            'height': '270',
            'type': 'iframe',
            'href': this.href,
            afterClose: function() {
                parent.location.reload(true);
            }
        });
        return false;
    });

    /**
        Birtir borða yfir myndina þegar notandi klikkar á bíómyndaposter
        til þess að merkja við þær myndir sem hann hefur séð.
    **/
    var imgId, cbxId, overlayId, bool;
    $(document).on("click", ".label", function clickAction(e) {
        imgId = "#" + e.target.id;
        cbxId = imgId.replace("img", "cbx");
        cbxIdPhp = cbxId.replace("#", "");
        overlayId = imgId.replace("img", "overlay");

        //bool=true ef hakað er í það, annars false.
        //Þessi breyta er send yfir í php fyrir database...
        if (!document.getElementById(cbxIdPhp).checked) {
            bool = "true";
        } else {
            bool = "false";
        }
        $(overlayId).toggle();

        //AJAX til að senda gögn yfir í php
        $.ajax({
            type: "POST",
            url: "../php/updatedb.php",
            data: {
                //checkbox ID fyrir php
                cbx: cbxIdPhp,
                //boolean fyrir checkbox
                bool: bool
            }
        })
    });

    /**
        Uppfærir checkbox eftir gildum í gagnagrunni þegar notandi skráir sig inn.
    **/
    $.ajax({
        url: "../php/getData.php",
        type: "GET",
        data: {},
        //Data er strengur af checkbox id's
        success: function(data) {
            //obj er vigur með checkbox id's
            var obj = JSON.parse(data);
            for (var i = 0; i < obj.length; i++) {
                var overlayTrue = obj[i].replace("cbx", "overlay");
                var cbxIdDerp = obj[i];
                $("#" + overlayTrue).show();
                $("#" + cbxIdDerp).prop('checked', true);
            };
        },
        error: function() {
            alert("Data could not be retrieved.");
        }
    });
});

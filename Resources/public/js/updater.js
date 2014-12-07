YuccaInSituUpdater = (function() {
    function live(eventType, elementSelector, cb) {
        document.addEventListener(eventType, function (event) {
            var elements = document.querySelectorAll(elementSelector);
            for (var elementIndex=0 ; elementIndex < elements.length; elementIndex++) {
                if (event.target == elements[elementIndex] ) {
                    cb.call(event.target, event);
                }
            }
        });
    }

    live("click", "[data-yucca-form-href]", function (event) {
        var width = 500,
            height = 500,
            x = 0,
            y = (screen.height - height) / 2
        ;
        window.open(
            this.getAttribute("data-yucca-form-href"),
            this.getAttribute("data-yucca-form-href"),
            "menubar=no, status=no, scrollbars=no, menubar=no, width="+width+", height="+height+", resizable=yes, left="+x+", top="+y
        );
    });
})();


let view = new Dgene({
    callBack: function () {
        // console.log(view);
        // view.dgeneScene.craeteVideo('Complex');
        // view.dgeneScene.applyFuse(['Complex']);
        view.dgeneScene.initKey();

        // $(document).keydown(function (event) {
        //     switch (event.keyCode) {
        //         case 81: //Q
        //             view.dgeneScene.callModelIndex(function (rsp) {
        //                 console.log(rsp);
        //             });
        //             break;
        //     }
        // });
        // view.dgeneScene.Fkey(81);
    }
});
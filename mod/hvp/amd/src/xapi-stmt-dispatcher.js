define(['jquery','h5p','adl'], function() {
    return {
        initialise: function () {
            ADL.XAPIWrapper.changeConfig({
                //'endpoint': 'http://demo.nextsoftwaresolutions.com/grassblade-lrs/xAPI/',
                //"auth" : "Basic " + toBase64('14-86ffb64c8768015:68092ad5616fe0e48e65df49c'),

                'endpoint': 'https://sandbox.watershedlrs.com/api/organizations/2991/lrs/',
                "auth": "Basic " + toBase64('VD1bpscl626lc0:nVyeOYRQRGH5I1')

            });
            H5P.externalDispatcher.on('xAPI', function (event) {
                console.log(event.data.statement);
                var stmt = new ADL.XAPIStatement(
                    event.data.statement.actor,
                    event.data.statement.verb,
                    event.data.statement.object);
                stmt.generateId();
                stmt.generateRegistration();
                console.log(JSON.stringify(stmt));
                ADL.XAPIWrapper.sendStatement(stmt);

            });
        }
    };
});

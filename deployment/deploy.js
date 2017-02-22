/**
 * Created by liu on 17-1-31.
 * Used to deploy the environment
 */

'use strict';

require('shelljs/global');

if (process.argv.length > 2) {
    var env = process.argv[2];
    var dir = `./deployment/${env}/`;
}

cp(dir + '.htaccess', './.htaccess');
cp(dir + 'config.php', './application/config/config.php');
cp(dir + 'constants.php', './application/config/constants.php');
cp(dir + 'index.php', './index.php');

//exec('node deployment/template.js');
//exec('node deployment/build.js');

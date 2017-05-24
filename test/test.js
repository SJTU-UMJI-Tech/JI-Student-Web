/**
 * Created by liu on 17-4-22.
 */
require('shelljs/global');
exec('npm run compile:template');
exec('npm run compile:build');
exec('npm run deploy:development');
exec('npm run deploy:production');
exec('npm run deploy:testing');

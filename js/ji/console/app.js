/**
 * Created by liu on 2017/2/24.
 */
"use strict";
define([
    'require', 'exports', 'module',
    'jquery', 'xterm', 'xterm/fullscreen/fullscreen', 'xterm/fit/fit'

], function (require, exports, module) {
    
    const $        = require('jquery'),
          Terminal = require('xterm'),
          $body    = $("#body-wrapper");
    
    module.exports = (options) => {
        
        $body.append('<div id="terminal" style="height: 1000px;"></div>');
        
        const term = new Terminal({
            cursorBlink : true,
            tabStopWidth: 4,
        });
        
        let path   = '~/';
        let prompt = [];
        
        term.open(document.getElementById('#terminal'));
        term.fit();
        term.toggleFullscreen();
        
        term.writeln('Welcome to the JI-Life Terminal Plugin');
        term.write(`$ ${path} `);
        
        term.on('key', function (key, e) {
            if (key && e.code.match(/^Key[A-Z]$/)) {
                term.write(key);
                prompt.push(key);
            } else {
                switch (e.code) {
                    case 'Backspace':
                        if (prompt.length > 0) {
                            term.write('\b \b');
                            prompt.pop();
                        }
                        break;
                    case 'Enter':
                        term.writeln('');
                        term.write(`$ ${path} `);
                        console.log(prompt.join(''));
                        prompt = [];
                        break;
                }
            }
            //console.log(key, e);
            
        });
    }
    
});
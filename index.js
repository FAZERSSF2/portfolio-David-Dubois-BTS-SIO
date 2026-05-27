import { init as initParticles, nextShape, prevShape } from '/portfolio-David-Dubois-BTS-SIO/assets/js/particles.js';
import { initScrollLogic } from '/portfolio-David-Dubois-BTS-SIO/assets/js/scroll.js';
import { UI } from '/portfolio-David-Dubois-BTS-SIO/assets/js/ui.js';

window.scrollTo(0, 0);

const system = initParticles();

const ui = new UI(
    () => {
        ui.setDisabled(true);
        nextShape().then((shape) => {
            if (shape) ui.updateText(shape.title, shape.description);
        }).finally(() => {
            ui.setDisabled(false);
        });
    },
    () => {
        ui.setDisabled(true);
        prevShape().then((shape) => {
            if (shape) ui.updateText(shape.title, shape.description);
        }).finally(() => {
            ui.setDisabled(false);
        });
    }
);

initScrollLogic(system.camera, system.controls, document.getElementById('webglCanvas'));


console.log(`%c
      |\\      _,,,---,,_
ZZZzz /, \`.-'\`'    -.  ;-;;,_
     |,4-  ) )-,_. ,\\ (  \`'-'
    '---''(_/--'  \`-'\\_) 

       WELCOME — David Dubois | BTS SIO SISR
`, "font-family: monospace; color: #38bdf8; font-size: 14px; font-weight: bold;");

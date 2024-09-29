import { Component } from '@angular/core';
import { CargaService } from 'src/factory/carga.service';

@Component({
    selector: 'app-ruta1',
    templateUrl: './ruta1.component.html',
    styleUrls: ['./ruta1.component.css']
})
export class Ruta1Component {
    constructor(
        private carga: CargaService
    ) {

    }
    ngOnInit() {
        setTimeout(() => {
            this.carga.pause();
        }, 200);
    }
}

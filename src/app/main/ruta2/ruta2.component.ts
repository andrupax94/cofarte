import { Component } from '@angular/core';
import { CargaService } from 'src/factory/carga.service';

@Component({
    selector: 'app-ruta2',
    templateUrl: './ruta2.component.html',
    styleUrls: ['./ruta2.component.css']
})
export class Ruta2Component {
    constructor(
        private carga: CargaService
    ) {

    }


}

import { Component } from '@angular/core';
import { CargaService } from 'src/factory/carga.service';
import { MensajesService } from 'src/factory/mensajes.service';
import { ModalesService } from 'src/factory/modales.service';

@Component({
    selector: 'app-main',
    templateUrl: './main.component.html',
    styleUrls: ['./main.component.css']
})
export class MainComponent {
    constructor(
        private carga: CargaService,
        private modales: ModalesService,
        private mensajes: MensajesService
    ) { }
    ngOnInit() {
        this.carga.pause();
        this.modales.open('disclaimer');
        this.mensajes.add('ok', 'esta ok');
    }
}

import { FilterService } from './filter.service';
import { Component, Renderer2 } from '@angular/core';
import { ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { CargaService } from 'src/factory/carga.service';
import { ChangeColorService } from 'src/factory/change-color.service';
import { ModalesService } from 'src/factory/modales.service';
@Component({
    selector: 'app-filter',
    templateUrl: './filter.component.html',
    styleUrls: ['./filter.component.css'],
    // encapsulation: ViewEncapsulation.None
})
export class FilterComponent {

    public pageFilter: string;

    // INFO CONSTRUCTOR
    constructor(private filter: FilterService,
        private renderer: Renderer2,
        private formBuilder: FormBuilder, private carga: CargaService, private modales: ModalesService) {
        this.pageFilter = 'ruta1';
    }




    cambiaPagina(e: MouseEvent, page: string = 'none') {
        this.filter.compartirPagina(page);
    }



    ngOnInit() {
        this.filter.pageSD$.subscribe(nuevosDatos => {

        });

    }
}

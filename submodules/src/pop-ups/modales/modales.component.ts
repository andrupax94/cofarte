import { Component, ViewEncapsulation } from '@angular/core';
import { ScrollColorDirective } from 'src/directives/scroll-color.directive';
import { ModalesService } from 'src/factory/modales.service';

@Component({
    selector: 'app-modales',
    templateUrl: './modales.component.html',
    styleUrls: ['./modales.component.css'],
    encapsulation: ViewEncapsulation.None
})
export class ModalesComponent {
    public typeModal;
    black: any;
    constructor(public modales: ModalesService) {
        this.typeModal = 'alert';
    }
    ngOnInit() {
        this.modales.SDtypeModalOB.subscribe((data) => {
            this.typeModal = data;
        });
    }

}

import { Directive, ElementRef, Input } from '@angular/core';
import { FactoryService } from 'src/factory/factory.module';
import * as $from from 'jquery';
@Directive({
    selector: '[getSvg]',
})
// segnda verson de getsvg agrega el svg pero con la propiedad mask-image en un elemento img sin ninguna etiqueta
//  dentro nada dentro de el, el elemento tiene que tener un width y height
export class GetsvgDirective {
    @Input('getSvg') gs: string = '';
    constructor(
        private element: ElementRef,
        private factory: FactoryService) {
        if (this.gs !== '')
            $(this.element.nativeElement).attr('getsvg', this.gs!);
        factory.getsvg(element.nativeElement);
    }

}

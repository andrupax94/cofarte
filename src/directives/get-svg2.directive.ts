import { Directive, ElementRef, Input } from '@angular/core';
import { FactoryService } from 'src/factory/factory.module';

@Directive({
    selector: '[getSvg2]'
})
export class GetSvg2Directive {
    @Input('getSvg2') gs: string = '';
    constructor(private element: ElementRef, private factory: FactoryService) {
        if (this.gs !== '')
            $(this.element.nativeElement).attr('getsvg2', this.gs!);
        factory.getsvg(element.nativeElement, '2');
    }

}


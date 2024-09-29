import { Directive, ElementRef, Input } from '@angular/core';

@Directive({
    selector: '[blankUrl]'
})
export class BlankUrlDirective {
    @Input() blankUrl: string = "";
    constructor(private element: ElementRef) { }
    ngOnInit() {
        $(this.element.nativeElement).click(() => {
            if (this.blankUrl !== '')
                window.open(this.blankUrl, '_blank');
        });
    }

}

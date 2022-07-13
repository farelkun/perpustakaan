import { Component, EventEmitter, Input, OnInit, Output, SimpleChange } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';
import { BookCategoryService } from '../../services/book-category.service';

@Component({
    selector: 'book-category-form',
    templateUrl: './book-category-form.component.html',
    styleUrls: ['./book-category-form.component.scss']
})
export class BookCategoryFormComponent implements OnInit {
    @Input() id: number;
    @Output() afterSave  = new EventEmitter<boolean>();
    mode: string;
    formModel : {
        id: number,
        name: string,
        description: string
    }

    constructor(
        private bookCategoryService: BookCategoryService,
        private landaService: LandaService
    ) {}

    ngOnInit(): void {

    }

    ngOnChanges(changes: SimpleChange) {
        this.emptyForm();
    }

    emptyForm() {
        this.mode = 'add';
        this.formModel = {
            id: 0,
            name: '',
            description: '',
        }

        if (this.id > 0) {
            this.mode = 'edit';
            this.getBookCategory(this.id);
        }
    }

    save() {
        if(this.mode == 'add') {
            this.bookCategoryService.createBookCategory(this.formModel).subscribe((res : any) => {
                this.landaService.alertSuccess('Successfully', res.message);
                this.afterSave.emit();
            }, err => {
                this.landaService.alertError('Sorry', err.error.errors);
            });
        } else {
            this.bookCategoryService.updateBookCategory(this.formModel).subscribe((res : any) => {
                this.landaService.alertSuccess('Successfully', res.message);
                this.afterSave.emit();
            }, err => {
                this.landaService.alertError('Sorry', err.error.errors);
            });
        }
    }

    getBookCategory(id) {
        this.bookCategoryService.getBookCategoryById(id).subscribe((res: any) => {
            this.formModel = res.data;
        }, err => {
            console.log(err);
        });
    }
}

import { Component, EventEmitter, Input, OnInit, Output, SimpleChange } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';
import { BookCategoryService } from '../../../book-categories/services/book-category.service';
import { BookService } from '../../services/book.service';

@Component({
    selector: 'book-form',
    templateUrl: './book-form.component.html',
    styleUrls: ['./book-form.component.scss']
})
export class BookFormComponent implements OnInit {
    @Input() id: number;
    @Output() afterSave  = new EventEmitter<boolean>();
    mode: string;
    listCategories: [];
    coverPreview: string;
    formModel : {
        id: number,
        title: string,
        author: string,
        publisher: string,
        isbn: string,
        cover: string,
        coverUrl: string,
        stock: number,
        description: string,
        status: string,
        book_category: [
            {
                id: number,
                name: string,
                description: string
            }
        ]
    };

    constructor(
        private bookService: BookService,
        private bookCategoryService: BookCategoryService,
        private landaService: LandaService
    ) {}

    ngOnInit(): void {
        this.getCategories();
    }

    ngOnChanges(changes: SimpleChange) {
        this.emptyForm();
    }

    emptyForm() {
        this.mode = 'add';
        this.formModel = {
            id: 0,
            title: '',
            author: '',
            publisher: '',
            isbn: '',
            cover: '',
            coverUrl: '',
            stock: 0,
            description: '',
            status: '',
            book_category: [
                {
                    id: 0,
                    name: '',
                    description: ''
                }
            ]
        }

        if (this.id > 0) {
            this.mode = 'edit';
            this.getBook(this.id);
        }
    }

    save() {
        if(this.mode == 'add') {
            this.bookService.createBook(this.formModel).subscribe((res : any) => {
                this.landaService.alertSuccess('Success', res.message);
                this.afterSave.emit();
            }, err => {
                this.landaService.alertError('Sorry', err.error.errors);
            });
        } else {
            this.bookService.updateBook(this.formModel).subscribe((res : any) => {
                this.landaService.alertSuccess('Berhasil', res.message);
                this.afterSave.emit();
            }, err => {
                this.landaService.alertError('Sorry', err.error.errors);
            });
        }
    }

    getBook(bookId) {
        this.bookService.getBookById(bookId).subscribe((res: any) => {
            this.formModel = res.data;
            console.log(res.data)
        }, err => {
            console.log(err);
        });
    }

    trackByIndex(index: number): any {
        return index;
    }

    back() {
        this.afterSave.emit();
    }

    fileChangeEvent(fileInput: any) {
        if (fileInput.target.files && fileInput.target.files[0]) {
            // Size Filter Bytes
            const max_size = 20971520;
            const allowed_types = ['image/png', 'image/jpeg'];
            const max_height = 15200;
            const max_width = 25600;
            const reader = new FileReader();
            reader.onload = (e: any) => {
                const image = new Image();
                image.src = e.target.result;
                image.onload = rs => {
                    const imgBase64Path = e.target.result;
                    this.coverPreview = imgBase64Path;
                    this.formModel.cover = imgBase64Path;
                    // this.previewImagePath = imgBase64Path;
                };
            };

            reader.readAsDataURL(fileInput.target.files[0]);
        }
    }

    getCategories() {
        this.bookCategoryService.getBookCategories([]).subscribe((res: any) => {
            this.listCategories = res.data.list;
            console.log(this.listCategories);
        }, err => {
            console.log(err);
        })
    }
}

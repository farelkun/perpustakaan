import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LandaService } from 'src/app/core/services/landa.service';
import Swal from 'sweetalert2';
import { BookCategoryService } from '../../services/book-category.service';

@Component({
    selector: 'list-book-category',
    templateUrl: './book-category-list.component.html',
    styleUrls: ['./book-category-list.component.scss']
})
export class BookCategoryListComponent implements OnInit {

    listBookCategories: [];
    titleModal: string;
    modelId: number;

    constructor(
        private bookCategoryService: BookCategoryService,
        private landaService: LandaService,
        private modalService: NgbModal
    ) { }

    ngOnInit(): void {
        this.getBookCategories();
    }

    trackByIndex(index: number): any {
        return index;
    }

    getBookCategories() {
        this.bookCategoryService.getBookCategories([]).subscribe((res: any) => {
            this.listBookCategories = res.data.list;
        }, (err: any) => {
            console.log(err);
        });
    }

    createBookCategory(modal) {
        this.titleModal = 'Add Book Category';
        this.modelId = 0;
        this.modalService.open(modal, { size: 'lg', backdrop: 'static' });
    }

    updateBookCategory(modal, bookCategoryModel) {
        this.titleModal = 'Edit Book Category: ' + bookCategoryModel.name;
        this.modelId = bookCategoryModel.id;
        this.modalService.open(modal, { size: 'lg', backdrop: 'static' });
    }

    deleteBookCategory(userId) {
        Swal.fire({
            title: 'Are you sure ?',
            text: 'Book category will be deleted',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#34c38f',
            cancelButtonColor: '#f46a6a',
            confirmButtonText: 'Yes, Delete this data !',
        }).then((result) => {
            if (result.value) {
                this.bookCategoryService.deleteBookCategory(userId).subscribe((res: any) => {
                    this.landaService.alertSuccess('Success', res.message);
                    this.getBookCategories();
                }, err => {
                    console.log(err);
                });
            }
        });
    }
}

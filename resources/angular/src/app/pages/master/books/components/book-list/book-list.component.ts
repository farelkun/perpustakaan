import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import Swal from 'sweetalert2';

import { LandaService } from 'src/app/core/services/landa.service';
import { BookService } from '../../services/book.service';

@Component({
    selector: 'book-list',
    templateUrl: './book-list.component.html',
    styleUrls: ['./book-list.component.scss']
})
export class BookListComponent implements OnInit {
    dtOptions: DataTables.Settings = {};
    listBooks: [];
    titleCard: string;
    modelId: number;
    isOpenForm: boolean = false;

    constructor(
        private bookService: BookService,
        private landaService: LandaService,
        private modalService: NgbModal
    ) { }

    ngOnInit(): void {
        this.getBook();
    }

    trackByIndex(index: number): any {
        return index;
    }

    getBook() {
        // this.bookService.getBooks([]).subscribe((res: any) => {
        //     this.listBooks = res.data.list;
        // }, (err: any) => {
        //     console.log(err);
        // });
        this.dtOptions = {
            serverSide: true,
            processing: true,
            ordering: false,
            searching: false,
            pagingType: "full_numbers",
            ajax: (dataTablesParameter: any, callback) => {

                const page = parseInt(dataTablesParameter.start) / parseInt(dataTablesParameter.length) + 1;
                const params = {
                    page: page,
                    offset: dataTablesParameter.start,
                    limit: dataTablesParameter.length,

                };
                this.bookService.getBooks(params).subscribe((res: any) => {
                    this.listBooks = res.data.list;
                    // this.totalItems = res.data.totalData;

                    setTimeout(() => {
                        var hidden = document.querySelector('.odd') as HTMLElement;
                        if (this.listBooks != []) {
                            hidden.style.display = 'none';
                        } else {
                            hidden.style.display = 'block';
                        }
                        //   this.progressService.finishLoading();
                    }, 500);

                    callback({
                        recordsTotal: res.data.meta.total,
                        recordsFiltered: res.data.meta.total,
                        data: []
                    });
                });
            },
        }
    }

    showForm(show) {
        this.isOpenForm = show;
    }

    createBook() {
        this.titleCard = 'Add Book';
        this.modelId = 0;
        this.showForm(true);
    }

    updateBook(bookModel) {
        this.titleCard = 'Edit Book: ' + bookModel.title;
        this.modelId = bookModel.id;
        console.log(bookModel)
        this.showForm(true);
    }

    deleteBook(userId) {
        Swal.fire({
            title: 'Are you sure ?',
            text: 'Book will be deleted',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#34c38f',
            cancelButtonColor: '#f46a6a',
            confirmButtonText: 'Yes, Delete this data !',
        }).then((result) => {
            if (result.value) {
                this.bookService.deleteBook(userId).subscribe((res: any) => {
                    this.landaService.alertSuccess('Success', res.message);
                    this.getBook();
                }, err => {
                    console.log(err);
                });
            }
        });
    }

}

import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { MatIconModule } from '@angular/material/icon';
import { LoginService } from '../../services/login/login.service';

@Component({
  selector: 'app-login',
  imports: [
    CommonModule,
    FormsModule,
    MatIconModule
  ],
  templateUrl: './login.html'
})
export class Login {
  email = '';
  password = '';
  error = '';
  isLoading = false;

  constructor(
    private auth: LoginService,
    private router: Router
  ) {}

  onSubmit() {
    this.error = '';
    this.isLoading = true;

    this.auth.login(this.email, this.password).subscribe({
      next: () => {
        this.router.navigate(['/categorias']);
      },
      error: err => {
        this.error = err?.message || 'Email ou senha incorretos';
        this.isLoading = false;
      },
      complete: () => {
        this.isLoading = false;
      }
    });
  }
}

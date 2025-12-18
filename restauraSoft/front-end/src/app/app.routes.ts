import { Routes } from '@angular/router';
import {Login} from './components/login/login';
import {Sidebar} from './components/core/sidebar/sidebar';

export const routes: Routes = [
  {
    path: "login",
    component: Login
  },
  {
    path: "sidebar",
    component: Sidebar,
    // children: [
    //   {
    //     path: "path",
    //     component: ComponentName
    //   }
    // ]
  }
];

import React from 'react';
import Portals from './Portals';

import TermContent from './term/TermContent';
import Sidebar from './sidebar/Sidebar';
import Search from './search/Search';
import Comments from './comments/Comments';
import AuthenticationRoot from './account/authentication/AuthenticationRoot';
import AdminRoot from './account/admin/AdminRoot';
import ProductsRoot from './product/ProductsRoot';

const App = () => {
  const term_content_root = document.getElementById("term-content_root");
  const sidebar_content_root = document.getElementById("sidebar-content_root");
  const search_content_root = document.getElementById("search-content_root");
  const post_comments_root = document.getElementById("post-comments_root");
  const profile_content_root = document.getElementById("profile-content_root");
  const product_root = document.getElementById("product_root");

  return <>
    <Portals container={term_content_root}><TermContent /></Portals>
    <Portals container={sidebar_content_root}><Sidebar /></Portals>
    <Portals container={search_content_root}><Search /></Portals>
    <Portals container={post_comments_root}><Comments /></Portals>
    <Portals container={profile_content_root}><AdminRoot /></Portals>
    <Portals container={product_root}><ProductsRoot /></Portals>
    <AuthenticationRoot />
  </>;
};

export default App;
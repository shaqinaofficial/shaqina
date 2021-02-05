-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 05, 2021 at 03:18 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_panel`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`cat_id`, `cat_name`) VALUES
(1, 'Bahan Bangunan'),
(2, 'Peralatan Listrik'),
(3, 'Sambungan Pipa');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice`
--

CREATE TABLE `tbl_invoice` (
  `inv_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nomor_hp` int(100) NOT NULL,
  `produk` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `provinsi` int(11) NOT NULL,
  `kota` int(11) NOT NULL,
  `kecamatan` int(11) NOT NULL,
  `alamat` varchar(100) NOT NULL,
  `metode_pembayaran` varchar(100) NOT NULL,
  `ongkir` int(11) NOT NULL,
  `fee` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `order_date` date NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_invoice`
--

INSERT INTO `tbl_invoice` (`inv_id`, `nama`, `nomor_hp`, `produk`, `harga`, `provinsi`, `kota`, `kecamatan`, `alamat`, `metode_pembayaran`, `ongkir`, `fee`, `total`, `order_date`, `status`) VALUES
(142, 'asdsadsa', 2134212131, 2, 22000, 2, 28, 435, 'asddsadsadsa', 'COD', 49000, 3000, 74000, '2021-02-05', 1),
(143, 'asdsadsa', 2134212131, 2, 22000, 2, 28, 435, 'asddsadsadsa', 'COD', 49000, 3000, 74000, '2021-02-05', 1),
(144, 'asdsadsa', 2134212131, 12, 22000, 2, 28, 435, 'asddsadsadsa', 'COD', 49000, 3000, 74000, '2021-02-05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `product_id` int(11) NOT NULL,
  `product_code` char(6) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `product_category` varchar(200) NOT NULL,
  `purchase_price` float(10,0) NOT NULL,
  `sell_price` float(10,0) NOT NULL,
  `stock` int(11) NOT NULL,
  `min_stock` int(11) NOT NULL,
  `product_berat` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `img` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `product_code`, `product_name`, `product_category`, `purchase_price`, `sell_price`, `stock`, `min_stock`, `product_berat`, `description`, `img`) VALUES
(1, 'TT0001', 'Triplek Tipis', 'Bahan Bangunan', 20000, 22000, 6, 5, 1000, 'Triplek 3 mm, berukuran 2440 mm x 122 mm x 3 mm', '5db30881c51a6.jpg'),
(2, 'TT0040', 'Triplek Sedang', 'Bahan Bangunan', 90000, 95000, 11, 7, 500, 'Triplek 9 mm, berukuran 2440 mm x 122 mm x 9 mm', '5dc02d63143c2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_status`
--

CREATE TABLE `tbl_status` (
  `status_id` int(11) NOT NULL,
  `nama_status` varchar(99) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_status`
--

INSERT INTO `tbl_status` (`status_id`, `nama_status`) VALUES
(0, 'Menunggu'),
(1, 'Diterima'),
(2, 'Ditolak');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` varchar(15) NOT NULL,
  `is_active` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `fullname`, `password`, `role`, `is_active`) VALUES
(1, 'admin', 'kntol', 'admin', 'Admin', 1),
(2, 'operator', 'hiyaaa', 'operator', 'Operator', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `cat_name` (`cat_name`),
  ADD UNIQUE KEY `cat_name_2` (`cat_name`);

--
-- Indexes for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  ADD PRIMARY KEY (`inv_id`),
  ADD UNIQUE KEY `inv_id` (`inv_id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_code` (`product_code`,`product_name`);

--
-- Indexes for table `tbl_status`
--
ALTER TABLE `tbl_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `inv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_status`
--
ALTER TABLE `tbl_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

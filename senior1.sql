-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2025 at 04:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `senior 1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `status` enum('active','inactive','banned') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`id`, `username`, `email`, `password_hash`, `registration_date`, `role`, `status`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$MUX.44ritG/A1T9Gxdr62.CrR7/JYY7sopCBUGPKPX3ceUOUVG1Sa', '2024-10-27 03:08:56', 'admin', 'active'),
(2, 'ali', 'ali890@gmail.com', '$2y$10$oBj0cqgIy5lKZ8piMTu6U.S1mS5FHGUXAiIaDeyjN7cbvfxTSo3MO', '2024-11-15 13:54:29', 'user', 'inactive'),
(7, 'miriana', 'mirianakhalil7@gmail.com', '$2y$10$P83DUWcWRkwDzCiJoYyv6.fJ8WadH5eIjbmTwhi3CeKEBI.SLc0Qu', '2024-10-27 17:05:04', 'admin', 'active'),
(8, 'amir', 'amir4@gmail.com', '$2y$10$TU15uaG7IxgzhgQ1.6r2deMshBhZmvAYfIHGLKAyirr.Tp33LEBUW', '2024-11-01 17:21:26', 'user', 'active'),
(20, 'karim', 'karim@gmail.com', '$2y$10$jSlv391w.suNIxFbmkY6M.h9DApZPUVEmJqG2EWAAPTXpz9PJcjwu', '2024-11-30 17:24:44', 'user', 'active'),
(21, 'ziad', 'ziad@gmail.com', '$2y$10$CNJ8MN5C1BSa6VsJFX3.R.8bdz21WgWKbIfuipcFyGLbJASoqaSGW', '2025-01-09 12:31:00', 'user', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `job_offer_id` int(11) DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `question1` text DEFAULT NULL,
  `question2` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `job_offer_id`, `resume`, `cover_letter`, `status`, `applied_at`, `question1`, `question2`) VALUES
(19, 1, 1, 'uploads/resumes/0-Lecture 0 (1).pdf', 'abc', 'approved', '2024-12-07 06:10:34', 'rr', 're'),
(20, 2, 1, 'uploads/resumes/0-Lecture 0 (1).pdf', 'nj', 'rejected', '2024-12-12 13:00:47', 'jk', 'n'),
(21, 1, 3, 'uploads/resumes/Bayes Theorim EX.docx', 'bn', 'approved', '2024-12-24 14:14:56', 'ef', 'de'),
(22, 2, 3, 'uploads/resumes/CSCI426-Web Programming Advanced.pdf', 'ff', 'approved', '2024-12-24 15:43:15', 's', 'g'),
(23, 2, 5, 'uploads/resumes/CSCI426_SampleFinal Exam_-1 (1).pdf', 'mk', 'approved', '2025-01-02 13:25:07', 'mk', 'mk'),
(24, 21, 6, 'uploads/resumes/CSCI426_SampleFinal Exam_.pdf', 'fyhgy', 'approved', '2025-01-09 11:37:46', 'htt', 'tf');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `introduction` text NOT NULL,
  `content` text NOT NULL,
  `final_thoughts` text NOT NULL,
  `video_title` varchar(255) DEFAULT NULL,
  `video_description` text DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `publish_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_name` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `inline_image_1` varchar(255) NOT NULL,
  `inline_image_2` varchar(255) NOT NULL,
  `inline_image_3` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `item_id`, `description`, `introduction`, `content`, `final_thoughts`, `video_title`, `video_description`, `author_id`, `publish_date`, `category_name`, `thumbnail`, `inline_image_1`, `inline_image_2`, `inline_image_3`, `video`) VALUES
(1, 'Rebecca Zahau - Justice or Mystery?', 1, 'Rebecca Zahau’s 2011 death remains shrouded in mystery\r\nand controversy. Officially ruled a suicide, the case has sparked\r\nwidespread debate due to unusual evidence, including signs\r\nof a struggle and a bizarre crime scene. Despite a civil trial \r\nthat held Adam Shacknai responsible, the case was never \r\nfully resolved, leaving lingering questions about\r\nthe true circumstances of her death.', 'The death of Rebecca Zahau in 2011 was a shocking event\r\nthat continues to captivate public attention. Found under \r\nsuspicious circumstances, the case highlights the challenges\r\nof balancing evidence, forensic science, and justice in high-profile investigations.', 'Rebecca\'s death occurred shortly after a tragic accident\r\ninvolving her boyfriend Jonah Shacknai\'s son, Max.\r\nThe young boy fell from a staircase while under\r\nRebecca\'s care and succumbed to his injuries\r\nshortly after. This tragedy set the stage for Rebecca\'\r\ndeath, which was officially ruled a suicide but contested\r\nby her family.\r\n\r\nRebecca’s autopsy revealed injuries inconsistent\r\nwith a suicide,such as hemorrhages under her scalp\r\nand marks suggesting a possiblestruggle. The unusual\r\ndiscovery of her body — bound, gagged, and hanging\r\nnude — further fueled speculation of foul play. Despite this,\r\ninvestigators cited a lack of physical evidence linking \r\nanyone to her death and maintained their conclusion\r\nof suicide.\r\n\r\nThe Zahau family’s persistence led to a civil trial \r\nin 2018, where Adam Shacknai was found liable for\r\nRebecca’s death. This ruling brought a sense of \r\nvindication for her family, but it was short-lived, \r\nas a subsequent settlement nullified the verdict. \r\nRebecca’s death remains a painful enigma,\r\nwith unresolved questions haunting those\r\nwho seek answers.', 'While the official narrative concludes suicide, Rebecca\r\nZahau’s case underscores the difficulty in resolving\r\ndeaths that involve contradictory evidence. \r\nForensic advancements and renewed interest may \r\none day provide clarity, but for now, the truth remains elusive.', '\"The Mysterious Death of Rebecca Zahau: A Deep Dive\"', 'the mysterious surrounding rebeccas death', 2, '2024-12-06 17:33:35', 'Cold Cases ', 'download (4).jfif', 'download (3).jfif', 'autopsyfilesorg-rebecca-zahau-autopsy-report.jpg', 'download (2).jfif', 'Woman dies mysteriously in historic California mansion 20 20 Part 1.mp4'),
(2, 'The Tragedy of Max Shacknai - A Life Cut Short', 1, 'This piece recounts the tragic fall of 6-year-old\r\nMax Shacknai at Spreckels Mansion, which led \r\nto his untimely death in 2011. The article reflect\r\non the emotional impact of the accident and its\r\nconnection to subsequent events, emphasizing\r\n the fragility of life and the ripple effects of loss.\r\n', 'Max Shacknai\'s death in July 2011 is \r\nan enduring tragedy that forever\r\naltered the lives of those involved.\r\nThe 6-year-old suffered a fatal fall\r\nat the Spreckels Mansion, sparking\r\nquestions about the circumstances\r\nleading to his accident and \r\nits aftermath.', 'Max was under the care of Rebecca Zahau when he\r\nfell from a second-story staircase, sustaining critical\r\ninjuries. The accident occurred while he was playing,\r\nand investigators concluded that Max tripped and \r\nfell over a railing while attempting to balance on a\r\nchandelier. Despite this explanation, some family members\r\nand commentators have expressed concerns about the \r\nlack of direct witnesses and conflicting details in the case.\r\n\r\nMax’s injuries included a fracture in his skull and damage to \r\nhis spinal cord. He was rushed to the hospital and placed \r\non life support but succumbed to his injuries five days later.\r\nThe incident had a profound emotional impact on his father\r\nJonah Shacknai, and likely contributed to the tragic events\r\nsurrounding Rebecca Zahau’s death two days later.\r\n\r\nWhile Max\'s death was ruled accidental, it has been \r\novershadowed by the controversy surrounding Rebecca\r\nZahau. However, his loss remains a deeply felt tragedy,\r\nparticularly for his family, who have sought privacy amidst\r\nthe media frenzy. The case reminds us of the fragility of life\r\nand the importance of safety in homes with children.', 'Max Shacknai’s death is a heartbreaking reminder of how\r\nfleeting life can be.\r\nThough his case was officially closed\r\nas an accident, its association \r\nwith subsequent events has left it indelibly marked in public memory, highlighting the ripple effects \r\nof tragedy.', '\"Max Shacknai: A Tragic Accident at Spreckels Mansion\"', '\"A look into the tragic fall of 6-year-old\r\nMax Shacknai at Spreckels Mansion in 2011\"', 2, '2024-12-08 12:11:58', 'Cold Cases ', 'max.jfif', 'max cd.jfif', 'download (5).jfif', 'max-shacknai-scene-5-29063637_220360_ver1.0_640_480.jpg', 'Dina Shacknai Son Max\'s death not an accident.mp4'),
(3, 'Unraveling the Cecil Hotel’s Dark Legacy Through Elisa Lam', 1, 'This article explores the strange and mysterious death \r\nof Elisa Lam, a 21-year-old student whose body was\r\nfound in a water tank at the Cecil Hotel in 2013. \r\nDespite the official ruling of accidental drowning, \r\nthe case remains shrouded in mystery,\r\nleading to widespread speculation and intrigue.', 'The tragic death of Elisa Lam is forever intertwined \r\nwith the infamous history of the Cecil Hotel, a place\r\nknown for its dark and disturbing past. Her case has\r\nbecome one of the most chilling chapters in the hotel’s \r\nlegacy, blending real-life horror with lingering mysteries \r\nthat continue to captivate people around the world.', 'The Cecil Hotel, built in 1924, is known for its grim history\r\nof suicides, violent incidents, and criminal activities. Elisa \r\nLam’s stay at the hotel became another eerie event in the\r\nhotel’s troubled past. During her stay, Elisa\'s behavior\r\nraised concerns, and she was moved from a shared room\r\nto a private room. Hotel surveillance footage shows her\r\nacting unusually, with strange movements and gestures in \r\nthe elevator, which have been analyzed and discussed by\r\n countless people worldwide.\r\n\r\nHer body was found in a rooftop water tank, and theories\r\nabout her death ranged from an accidental drowning\r\ninfluenced by a manic episode to suggestions of murder\r\nor supernatural involvement. The hotel\'s reputation for \r\nparanormal activity only heightened the theories, especially\r\nwhen some speculated that the surveillance video had been\r\nedited to conceal certain details. Additionally, Elisa\'s death \r\ncoincided with a tuberculosis outbreak in Los Angeles, \r\nwhere one of the diagnostic tests was named LAM-ELISA,\r\nfurther fueling conspiracy theories, even though the \r\nconnection was purely coincidental.\r\n\r\nDespite the speculation, the official ruling was that Elisa’s bipolar\r\ndisorder contributed to her erratic behavior, leading to her tragic death.\r\nHowever, skepticism persists, and the unresolved elements of her case\r\ncontinue to spark discussions about the Cecil Hotel’s role in her fate.', 'The case of Elisa Lam and the Cecil Hotel serves as a reminder of the\r\nchilling intersection of fact, fiction, and fear. It highlights how a tragic\r\ndeath can lead to the blending of reality with myth, and the power\r\n of unresolved mysteries to capture the collective imagination.', 'The Mysterious Death of Elisa Lam – A Case of Unanswered Questions', ' This video delves into the\r\n mysterious death of Elisa Lam', 2, '2024-12-06 17:34:55', 'Cold Cases ', 'download (23).jfif', 'download (24).jfif', 'download (14).jfif', 'download (15).jfif', 'Surveillance video of Elisa Lam shows bizarre behavior.mp4'),
(4, ' Kenneka Jenkins - Investigations and Public Outcry', 1, 'This article examines the investigation into Kenneka \r\nJenkins’ death, focusing on the delays in releasing \r\nsecurity footage and the public\'s mistrust of the findings. \r\nIt also covers the family\'s legal action against the hotel\r\nfor negligence, highlighting ongoing concerns\r\n about accountability and transparency.', 'Investigations and Public Outcry\r\nIntroduction\r\nKenneka Jenkins’ death shocked the nation not only because \r\nof its tragic nature but also due to the numerous unanswered \r\nquestions and the way the investigation unfolded.\r\nFueled by viral social media discussions and widespread\r\nskepticism, the case became a focal point for public\r\n outrage and conspiracy theories.', 'From the start, the investigation into Kenneka’s death\r\nfaced criticism. Her mother, Teresa Martin, voiced her \r\nfrustration over the delayed release of security footage\r\n by the hotel staff. The delay in accessing this footage, \r\nwhich showed Kenneka stumbling in the hotel hallways, \r\nfurther fueled suspicions of foul play. The gaps in the \r\nvideo recordings only intensified conspiracy theories,\r\nwith some speculating that her friends or even hotel \r\nstaff may have been involved in her disappearance.\r\nHowever, despite these claims, the investigation found\r\nno evidence to support the allegations.\r\n\r\nUltimately, the authorities ruled Kenneka’s death as an\r\naccidental case of hypothermia due to intoxication. \r\nPublic trust in the investigation, however, remained low.\r\nThe family pursued legal action, filing a lawsuit against the hotel,\r\nwhich resulted in a $10 million settlement. The lawsuit accused \r\nthe hotel of negligence, particularly in their failure to properly\r\nsecure the freezer area and review security footage promptly.', 'The Kenneka Jenkins case serves as a cautionary tale about\r\n the dangers of lack of transparency and the potential \r\nfor misinformation to overshadow the truth. \r\nWhile legal action has provided some closure for her \r\nfamily, the lingering questions about the investigation\r\nhighlight the need for greater accountability in \r\nhandling such cases.', 'The Investigation Into Kenneka Jenkins\' Death: Unanswered Questions\"', '\"This video discusses the flaws in the \r\ninvestigation into Kenneka Jenkins\' death\"', 2, '2024-12-08 12:13:25', 'Cold Cases ', 'download (8).jfif', 'kn.jfif', 'images.jfif', 'kn1.jfif', 'Final Moments Of Kenneka Jenkins Seen On Video.mp4'),
(10, 'The Unanswered Questions Behind Kris and Lisanne’s Disappearance', 1, 'The disappearance of Kris Kremers and Lisanne Froon in\r\nPanama continues to intrigue and baffle the public. Despite\r\nextensive investigations and cryptic photos found on their\r\ncamera, questions about whether their deaths were accidental\r\nor caused by foul play remain unresolved.', 'The disappearance of Kris Kremers\r\nand Lisanne Froon in Panama\'s \r\njungle has left investigators \r\nand the public grappling with \r\nquestions for nearly a decade.\r\n Despite extensive searches and \r\nthe recovery of cryptic evidence,\r\n their story is a perplexing blend\r\n of tragedy, mystery, and unresolved theories.', 'From the outset, the investigation into Kris and Lisanne’s \r\ndisappearance faced challenges. The dense jungle hindered\r\nsearch efforts, and the discovery of Lisanne’s backpack\r\nweeks later raised more questions than answers.\r\n\r\nTheir phones revealed attempts to call emergency services\r\nas early as April 1, indicating that they realized they were\r\n in trouble shortly after beginning their hike. Disturbingly,\r\nthe camera included over 90 photos taken days after their\r\ndisappearance. While some depicted the women smiling \r\nat the start of their hike, later images included pitch-black\r\nphotos and close-ups of rocks and foliage, sparking theories\r\nthat they were signaling for help or marking their path.\r\n\r\nThe recovery of skeletal remains deepened the mystery.\r\nExperts debated whether the scattering of bones was due\r\nto natural processes, such as animal activity, or something\r\nmore sinister. Speculation ranged from accidental deaths to\r\nfoul play, with theories involving human intervention in the \r\nwomen\'s final hours.', 'While the official conclusion leans toward accidental deaths, \r\nmany unanswered questions persist. The case of Kris Kremers\r\nand Lisanne Froon underscores the enduring uncertainty surrounding \r\nsome tragedies, leaving their families and the world searching for closure.', ' The Truth Behind Kris and Lisanne\'s Mysterious Disappearance', 'Delve deeper into the mysterious case of Kris Kremers and Lisanne Froon. \r\nDespite forensic analysis and extensive search efforts, their disappearance \r\nremains filled with unanswered questions. This video explores the evidence,\r\nthe theories, and the ongoing search for answers regarding what happened\r\n to the two young travelers in Panama.', 2, '2024-12-06 18:14:53', 'Unsolved Mysteries', 'download (5).jfif', 'download (6).jfif', 'download (7).jfif', 'download (8).jfif', 'The same V-shaped tree has been photographed for hours - night photos Kris and Lisanne Disappearance.mp4'),
(11, 'The Tragic Death of Faye Swetlik: Unraveling the Mystery', 1, 'In 2019, 6-year-old Faye Swetlik disappeared from her home in \r\nSouth Carolina, and her body was discovered days later in a \r\nwooded area nearby. The case shocked the local community \r\nand raised unsettling questions about the involvement of a\r\nneighbor, Coty Scott Taylor, who was found dead in an apparent\r\nsuicide after Faye’s body was recovered. The investigation \r\nconcluded that Taylor was responsible for the crime, but the\r\n details surrounding his motives and actions remain a mystery,\r\n leaving the case unresolved.', 'he disappearance and murder of 6-year-old Faye Swetlik in February\r\n2019 remains one of South Carolina’s most chilling and mysterious \r\ncases. Faye’s sudden vanishing from her home and the subsequent \r\ndiscovery of her body left the local community devastated and \r\nsearching for answers. Coty Scott Taylor, a neighbor who was \r\nlinked to the crime, was found dead in his home shortly after Faye’s\r\n body was discovered, leaving many questions unanswered about\r\n the true nature of the crime.', 'On February 10, 2019, Faye Swetlik disappeared while playing outside\r\n her home in Cayce, South Carolina. Her family immediately alerted \r\nauthorities, launching a frantic search for the young girl. Investigators\r\n quickly discovered surveillance footage that placed a neighbor, Coty \r\nTaylor, near the Swetlik residence shortly after Faye was last seen. Taylor, \r\n30 years old, had no previous criminal record, yet he became\r\n the focus of the investigation.\r\n\r\nThree days later, Faye’s body was discovered in a wooded area not far\r\n from her home. In an unexpected turn, Coty Taylor was found dead \r\nfrom an apparent suicide, just as police were closing in on him as the\r\n primary suspect. While investigators officially concluded that Taylor\r\n was responsible for Faye’s murder, many details of the crime remain \r\nunclear. There is little known about his motives, and the exact \r\ncircumstances surrounding Faye’s death have left the community \r\nwith unanswered questions. Some have speculated that Taylor may \r\nhave had a prior connection to the family, though nothing has been\r\n definitively proven.\r\n\r\nFaye’s tragic death has sparked public outrage and many theories\r\n about what happened, particularly regarding Taylor’s actions and\r\n his possible involvement in her abduction. The fact that he killed\r\n himself without offering any explanation has only deepened the mystery.', 'The case of Faye Swetlik’s death remains one of sorrow and uncertainty. \r\nWhile the investigation determined that Coty Taylor was responsible for \r\nher murder, the motives behind the crime, along with the details of how\r\n it unfolded, continue to puzzle both authorities and the public. Faye’s\r\n case highlights the emotional toll that such unsolved mysteries leave\r\n on families and communities, with many unanswered questions still lingering.', 'The Tragic Case of Faye Swetlik: The Unsolved Mystery of Her Murder', 'This video takes a deep dive into the heartbreaking case of 6-year-old Faye Swetlik, who was found dead in 2019 after disappearing from her South Carolina home. Investigating the involvement of her neighbor, Coty Taylor, whose death by suicide left many questions unanswered, the video explores the ongoing mystery and the unanswered motives behind Faye\'s tragic death.', 2, '2024-12-08 12:15:43', 'Unsolved Mysteries', 'ad88b36c-8e57-45f9-a1d4-5d77dea4709c-JM.fayeswtlik.022120.027.webp', 'fy1.jfif', 'download (9).jfif', 'fy2.jfif', 'Neighbor killed Faye Swetlik the day she went missing, killed himself days later, police say.mp4'),
(12, 'Ted Bundy: The Charming Killer', 1, 'Ted Bundy was an infamous American serial killer whose intelligence\r\n and charm masked his brutal crimes. He preyed on young women \r\nacross multiple states in the 1970s, ultimately becoming one \r\nof the most notorious criminals in American history.', 'Ted Bundy is one of the most \r\nnotorious serial killers in \r\nAmerican history. Known for his\r\n charm and intelligence, Bundy’s\r\n heinous crimes during the 1970s\r\n left a mark on the annals of \r\ncriminal history.', 'Ted Bundy used his charismatic persona to lure young women \r\ninto vulnerable situations. Pretending to have a disability or \r\nposing as an authority figure, Bundy would gain their trust \r\nbefore abducting and brutally murdering them. His victims \r\noften ranged from college students to young professionals. \r\nThe majority of his killings spanned across states like Washington,\r\n Utah, Colorado, and Florida. Despite his outward appearance as a\r\n \"model citizen,\" Bundy’s dark tendencies were undeniable.\r\n Authorities estimate he killed at least 30 women, although the\r\n exact number remains unknown. His eventual capture in 1978\r\n was due to meticulous police work and the efforts of survivors\r\n who shared critical details. After several trials, Bundy was \r\nexecuted in the electric chair in 1989.', 'Ted Bundy’s case remains a chilling reminder that evil can hide \r\nin plain sight. His story has been a subject of numerous books,\r\n documentaries, and movies, sparking debates about the\r\n psychological and societal factors that create such individuals.', 'Ted Bundy’s Dark Charisma', 'A deep dive into the psychological manipulations of Ted\r\nBundy and how his charm was used as a tool for his horrific acts.', 2, '2024-12-07 06:25:23', 'Serial Killings ', 'download (16).jfif', 'download (17).jfif', 'download (18).jfif', 'download (19).jfif', 'Who Was Ted Bundy.mp4'),
(13, 'Gary Ridgway: The Green River Killer', 1, 'Gary Ridgway, known as the \"Green River Killer,\" \r\nis one of the most prolific serial killers in U.S. history.\r\n He killed at least 49 women during the 1980s and 1990s,\r\n targeting vulnerable victims and evading capture for decades.', 'Gary Ridgway, infamously dubbed the\r\n\"Green River Killer,\" is remembered\r\n as one of the deadliest serial \r\nkillers in the United States. \r\nHis reign of terror spanned the\r\n 1980s and 1990s.', 'Ridgway targeted vulnerable women, often sex workers or \r\nthose struggling with difficult circumstances. His method \r\nwas chillingly methodical; he would strangle his victims and\r\n dispose of their bodies along the Green River and other \r\nsecluded areas in Seattle. Over the years, Ridgway confessed\r\n to murdering 49 women, although it’s suspected the actual \r\nnumber may be much higher.\r\n\r\nFor years, his crimes baffled investigators, and it wasn’t until 2001 that advancements in DNA technology linked Ridgway to several murders. He was apprehended and later convicted, receiving a life sentence without the possibility of parole.\r\n\r\nRidgway’s case became a landmark in forensic science, underscoring the importance of preserving evidence and the persistence required to solve cold cases.', 'The \"Green River Killer\" epitomizes the terror of a predator who\r\n operated undetected for decades. His capture provided a\r\n semblance of justice for the victims and their families, \r\nwhile also highlighting the crucial role of modern science\r\n in solving crimes.', 'The Hunt for Ridgway', 'An in-depth examination of the Green River Killer’s\r\ncriminal activities, his eventual capture, and the \r\nscience behind his conviction.', 2, '2024-12-07 06:29:38', 'Serial Killings ', 'download (20).jfif', 'download (21).jfif', 'download (22).jfif', 'download (23).jfif', 'Gary Ridgway Interview with the Green River Killer.mp4'),
(14, 'The Chris Watts Case (2018)', 1, 'A chilling account of Chris Watts\' brutal murder of his pregnant wife,\r\nShanann, and their two young daughters, Bella and Celeste, \r\nin August 2018, and the shocking revelations that followed his confession.', 'The murder of Shanann Watts and her daughters, Bella and Celeste,\r\n by Chris Watts shocked the world, especially as their seemingly \r\nperfect family life unraveled in the aftermath. Watts initially denied\r\n his involvement, but evidence soon led to his confession, revealing\r\n the dark side of his actions.', 'In August 2018, Chris Watts murdered his pregnant wife, Shanann, \r\nand their two daughters in their Colorado home. Shanann was 15 \r\nweeks pregnant at the time. Initially, Chris denied any involvement\r\n in the disappearance of his family, but after a series of interrogations\r\n and confronting evidence, he confessed to the murders. \r\nWatts strangled his wife in their bedroom, then smothered his daughters,\r\n Bella and Celeste, before disposing of their bodies at an\r\n oil site where he worked.\r\n\r\nThe case became a media sensation, partly because of the Watts family\'s apparent perfect life portrayed on social media. Shanann’s Facebook posts often showed happy family moments, which made the tragedy even more shocking. Chris Watts was arrested on August 15, 2018, and later pled guilty to all three murders. He was sentenced to life in prison without the possibility of parole.\r\n\r\nInvestigators revealed that the motive behind the murders was Chris\'s extramarital affair, as he had been involved with a woman named Nichol Kessinger. He claimed that he wanted to start a new life with her, leading to the brutal killings of his wife and children. The Watts case is an example of how a seemingly normal family life can be shattered by dark secrets and horrific violence.', 'The Chris Watts case serves as a tragic reminder of the potential\r\n for hidden violence behind closed doors. It also sheds light on \r\nthe dangers of infidelity, manipulation, and the dark psychological\r\nfactors that can lead to unimaginable crimes. This case continues\r\n to haunt the public, particularly because of the social media\r\n portrayal of a perfect family that concealed dark truths.', 'The Chris Watts Case: Deception, Lies, and Murder', 'A detailed look into the Chris Watts case, from the discovery\r\n of the bodies to his confession and sentencing, and the \r\ndark truths that led to the brutal murders.', 2, '2024-12-07 07:04:01', 'Domestic Killings', 'watts.jfif', 'watts1.jfif', 'wats2.jfif', 'download (12).jfif', 'RAW Chris Watts, husband of missing Frederick woman, interviewed by Denver7\'s Tomas Hoppough.mp4'),
(15, 'The Ganon Stauch Case (2020)', 1, 'A haunting account of the disappearance and murder of 11-year-old\r\n Ganon Stauch, allegedly by his stepmother, Letecia Stauch, \r\nin Colorado in 2020. The case involved manipulation, lies, \r\nand a disturbing series of events that led to Ganon’s tragic death.', 'The disappearance and subsequent murder of Ganon Stauch\r\n became a heartbreaking case that captured national attention. \r\nWhat initially seemed like a tragic missing-child case turned \r\ninto a nightmare as the truth about his stepmother’s involvement\r\n slowly came to light.', 'Ganon Stauch, an 11-year-old boy, disappeared from his home in\r\n Colorado Springs on January 27, 2020. His stepmother, Letecia Stauch,\r\n claimed that Ganon had run away after an argument, but investigators\r\n quickly discovered inconsistencies in her story. Letecia’s account of\r\n the events surrounding his disappearance was full of contradictions,\r\n and her behavior raised red flags.\r\n\r\nAfter weeks of intense searching, authorities discovered Ganon’s body\r\n in a remote area of Colorado, and Letecia was arrested on charges of\r\n first-degree murder, tampering with a deceased body, and other\r\n related charges. Investigators determined that Ganon had been killed\r\n before his body was discarded in a nearby area. Autopsy results revealed\r\n that Ganon died from blunt force trauma, and Letecia’s involvement in his\r\n death was confirmed.\r\n\r\nDuring the investigation, it was revealed that Letecia had a history of manipulative behavior and had been lying about various details surrounding Ganon’s disappearance. Her motives were believed to be tied to personal issues with her relationship and her life at home. The case shocked the public, especially as Ganon had been portrayed as a happy child with a bright future before his untimely death. Letecia Stauch was later charged with murder and is facing a potential life sentence.', 'The Ganon Stauch case is a heartbreaking example of how trust\r\n and familial bonds can be betrayed in the most unimaginable \r\nways. The case also underscores the dangers of manipulation \r\nand deception within families, and how lies can prolong justice\r\n for the victims. Ganon’s story remains a tragic reminder of the\r\n horrors that can happen behind closed doors, even within what\r\n appears to be a normal family.', 'The Ganon Stauch Case: Lies, Deception, and Tragedy', 'A detailed exploration of the disappearance and murder of \r\nGanon Stauch, the investigation, and the disturbing role \r\nof his stepmother, Letecia Stauch.', 2, '2024-12-07 07:29:08', 'Domestic Killings', 'g1.jfif', 'g2.jfif', 'g3.jfif', 'j4.jfif', '\'Why Letecia Why\' Brother of Accused Child Killer Breaks Down.mp4'),
(16, 'The Disappearance of Natalie Holloway: A Mystery That Endured', 1, 'The mysterious 2005 disappearance of Natalie Holloway during\r\n a graduation trip to Aruba, and the ongoing search for justice\r\n and closure for her family.', 'The case of Natalie Holloway, a young American woman who disappeared\r\n in 2005 while on a graduation trip in Aruba, has haunted the public for\r\nyears. Despite extensive investigations, her fate remains unknown, and the \r\ncase continues to provoke questions about justice, media influence, and the\r\n investigation process.', 'On May 30, 2005, Natalie Holloway, an 18-year-old from Alabama, vanished\r\n during a trip to Aruba to celebrate her high school graduation. She was last\r\n seen leaving a nightclub with Joran van der Sloot and two other men. \r\nDespite an intensive search and various theories about her disappearance,\r\n Holloway’s whereabouts were never determined.\r\n\r\nAuthorities focused on van der Sloot, who was the prime suspect, \r\nas well as other local men, but their investigation was marred by missteps\r\n and lack of concrete evidence. Over the course of the investigation, van der\r\n Sloot’s changing stories and eventual involvement in another crime — the \r\nmurder of Stephany Flores in Peru — added layers of complexity to the case.\r\n\r\nHolloway’s case gained significant media attention, with her mother, Beth\r\n Holloway, becoming a prominent voice in advocating for the search. \r\nWhile there was no body found, and no charges related to her disappearance,\r\n the public remains deeply invested in the search for answers. The case raised\r\n issues surrounding international law enforcement, jurisdiction, and the \r\nchallenges of solving missing persons cases across borders.\r\n\r\nDespite her disappearance, Natalie’s legacy continues to be a symbol of hope \r\nfor families of missing persons. Her case also led to increased awareness \r\nabout the risks young travelers face and prompted legal changes to improve\r\n safety in tourist destinations.', 'The disappearance of Natalie Holloway remains one of the most enduring \r\nand tragic mysteries of our time. It highlights the complexities and limitations\r\n of international investigations and serves as a reminder of the emotional toll\r\n on families when justice remains elusive. While Holloway\'s fate is still unknown,\r\n her case has contributed to broader discussions about missing persons,\r\n crime investigations, and the role of media in influencing outcomes.', 'The Natalie Holloway Case: What We Know', 'A timeline video covering the key moments of Natalie Holloway\'s\r\n disappearance, the investigation, and the ongoing search for answers.', 2, '2024-12-07 11:00:43', 'High Profile Cases', 'nat1.jfif', 'nat2.jfif', 'nat3.jfif', 'nat4.jfif', 'Joran van der Sloot Confesses to Natalee Holloway’s Murder.mp4'),
(17, 'The Disappearance of Elizabeth Smart: Kidnapping, Survival, and Recovery', 1, 'The shocking 2002 abduction of Elizabeth Smart, her survival, and\r\n the years-long legal process that followed, which raised important\r\n issues on child abduction and recovery.', 'The 2002 kidnapping of Elizabeth Smart from her Salt Lake City\r\n home was a traumatic event that captivated the world. Her safe\r\n return after nine months in captivity and the subsequent trial of \r\nher abductors raised critical questions about child abduction and\r\n the long-term effects on survivors.', 'On June 5, 2002, Elizabeth Smart, a 14-year-old girl from Salt Lake City,\r\n was abducted from her home by Brian David Mitchell and his wife, \r\nWanda Barzee. The couple had planned her abduction for months and\r\n kept Elizabeth hidden in the mountains for the duration of her captivity.\r\n\r\nElizabeth endured emotional and physical abuse during her time in captivity.\r\nMitchell claimed he had married Elizabeth and repeatedly sexually assaulted her. \r\nDespite these horrific experiences, Elizabeth showed remarkable resilience, \r\nremaining strong and keeping hope alive. Over the months, she was moved\r\naround and at times even seen in public with Mitchell and Barzee, \r\nyet no one suspected the abduction.\r\n\r\nAfter nine months of searching, Elizabeth was recognized in a Salt Lake\r\nCity suburb by two people who alerted authorities, leading to Mitchell \r\nand Barzee\'s arrest. Elizabeth was found alive on March 12, 2003,\r\n and was joyously reunited with her family.\r\n\r\nIn the following years, both Mitchell and Barzee were convicted. Mitchell, \r\nwho was mentally incompetent to stand trial initially, was later convicted for\r\nthe kidnapping and sexual abuse of Elizabeth. Barzee was sentenced to 15\r\nyears in prison but was released early after cooperating with the authorities.\r\n\r\nElizabeth\'s case became a powerful symbol of survival. In the years following\r\nher recovery, she became an advocate for child safety, working to raise\r\nawareness about child abduction and advocating for other survivors of violent crimes.', 'The case of Elizabeth Smart stands as a testament to the strength of the\r\nhuman spirit. Her resilience in surviving captivity and her subsequent\r\nadvocacy for child protection has inspired many. Elizabeth’s story also\r\n sheds light on the importance of early intervention and awareness in\r\n preventing abductions. The case remains one of the most significant \r\nchild abduction stories of the 21st century, highlighting the need for \r\nongoing efforts to protect children from harm.', 'Elizabeth Smart: A Story of Survival', 'A video recounting Elizabeth Smart\'s abduction, her survival in captivity,\r\n and her eventual recovery, including key moments from the trial of her \r\nabductors.', 2, '2024-12-07 11:18:15', 'High Profile Cases', 'smart1.jfif', 'smart2.jfif', 'smart3.jfif', 'smart 7.jfif', 'Elizabeth Smart looks back at her dramatic rescue   Nightline.mp4'),
(18, 'The Trial of Elizabeth Holmes: The Theranos Scandal and Its Consequences', 1, 'Elizabeth Holmes, the founder of Theranos, was convicted of\r\n defrauding investors and patients by falsely claiming her \r\nblood-testing technology could detect diseases from a single\r\n drop of blood. This article delves into the details of the trial \r\nand its implications for the world of biotech and corporate ethics.', 'In 2022, Elizabeth Holmes, once \r\ncelebrated as a Silicon Valley\r\n visionary, stood trial for \r\ndefrauding investors, patients,\r\n and doctors by claiming her \r\ncompany, Theranos, had developed\r\n a groundbreaking blood-testing\r\n technology. The technology, \r\nwhich was supposed to revolutionize\r\ndiagnostics, was revealed to be \r\nfaulty and inaccurate, leading\r\nto one of the most high-profile \r\ncorporate fraud cases in recent \r\nhistory. This article examines \r\nthe trial\'s events, the evidence presented, and the lasting impact\r\nof the scandal.', 'Elizabeth Holmes\' rise to fame began with her promise to\r\n revolutionize the medical world with a blood-testing device\r\ncapable of detecting multiple diseases from just a drop of \r\nblood. However, as the company grew, it became clear that\r\n the technology was not functioning as promised. Holmes \r\nand her associates misled investors and the public, inflating\r\n the potential of the technology. The trial in 2022 brought to\r\n light the extent of the deception, with Holmes facing charges\r\n of defrauding investors and patients. After a lengthy legal battle, \r\nHolmes was convicted and sentenced to 11 years in prison for\r\n her role in the fraud.', 'The Theranos scandal is a cautionary tale about the dangers \r\nof ambition unchecked by ethical considerations. Elizabeth \r\nHolmes’ trial highlighted the importance of transparency, \r\naccountability, and integrity in the tech and healthcare \r\nindustries. The case serves as a reminder of the devastating\r\n consequences of corporate fraud and the need for robust \r\nregulations to prevent such deceitful practices in the future.', '\"The Rise and Fall of Elizabeth Holmes: The Theranos Trial\"', ' This video takes a deep dive into the trial of Elizabeth Holmes,\r\n examining the key moments, evidence, and the broader \r\nimplications of the Theranos scandal. Watch as we analyze\r\n the court case that shook Silicon Valley and the healthcare world.', 2, '2024-12-08 14:28:05', 'legal and court cases ', 'lez.jfif', 'lez1.jfif', 'leez2.jfif', 'le3.jfif', '\'The Dropout\' The rise and fall of Elizabeth Holmes, Theranos.mp4'),
(19, 'The Trial of Derek Chauvin: The Murder of George Floyd and Its Impact', 1, 'The trial of Derek Chauvin, the police officer who murdered George\r\n Floyd in 2020, became a pivotal moment in the fight for racial\r\n justice. This article examines the trial\'s significance, the \r\nevidence presented, and its role in the broader movement \r\nfor police reform.', 'The killing of George Floyd by Minneapolis police officer Derek \r\nChauvin in May 2020 sparked a global outcry and led to \r\nwidespread protests against police brutality and racial injustice.\r\n The video of Chauvin kneeling on Floyd\'s neck became a \r\nsymbol of systemic racism and inequality in law enforcement.\r\n Chauvin\'s subsequent trial in 2021 was one of the most \r\nsignificant in recent American history, both for its legal outcomes\r\n and its social impact. This article explores the trial, its outcome,\r\n and the broader conversations it sparked.', 'On May 25, 2020, Derek Chauvin, a Minneapolis police officer, was\r\n filmed kneeling on the neck of George Floyd, an unarmed Black \r\nman, during an arrest. The video of the incident, widely shared \r\nacross social media, led to protests in the United States and around \r\nthe world, demanding justice for Floyd and an end to police violence.\r\n Chauvin was arrested and charged with murder and manslaughter. \r\nHis trial in 2021 became a focal point for national conversations \r\nabout police reform, racial injustice, and the Black Lives Matter \r\nmovement. In April 2021, Chauvin was convicted of second-degree \r\nunintentional murder, third-degree murder, and second-degree\r\n manslaughter, and sentenced to 22.5 years in prison.', 'The trial of Derek Chauvin was a watershed moment in the fight for \r\nracial justice and police reform. His conviction, while significant, \r\nrepresents only one step toward addressing the systemic issues \r\nof racism and violence within law enforcement. The George Floyd\r\n case galvanized a global movement that continues to push for\r\n lasting change in the way police interact with communities, \r\nparticularly communities of color. The fight for justice is far from \r\nover, and Chauvin’s conviction remains a symbol of the broader \r\nstruggle for equality and reform.', '\"Derek Chauvin\'s Trial: Justice for George Floyd\"', 'This video provides an in-depth look at the trial of Derek\r\n Chauvin, examining the charges, key moments from the\r\n court proceedings, and the profound impact of George \r\nFloyd\'s death on the world. Join us as we explore the trial\r\n and its lasting legacy in the fight for racial justice.', 2, '2024-12-08 14:40:02', 'legal and court cases ', 'flod1.jfif', 'fold2.jfif', 'fold3.jfif', 'fold4.jfif', 'Derek Chauvin sentenced to 22 1 2 years in prison for George Floyd\'s murder   ABC7.mp4'),
(20, 'The Fundamentals of Criminal Law: Understanding Crimes and Penalties', 1, 'This article will explore the broad categories of criminal law,\r\ndetailing specific types of crimes, penalties, and their societal impact.', 'Criminal law is the body of law that relates to crime. It defines offenses, prescribes penalties, and regulates how\r\nthe state prosecutes individuals accused of violating the law. Criminal law is \r\ndesigned to protect citizens, deter criminal behavior, and maintain public order.', 'Categories of Crime:\r\nFelonies: Serious crimes such as murder, rape, armed robbery, and drug trafficking. Felonies often carry severe penalties, including imprisonment for more than one year, and in some jurisdictions, the death penalty.\r\nMisdemeanors: Less severe crimes like petty theft, simple assault, and public intoxication. Misdemeanors usually result in shorter jail sentences or fines.\r\nInfractions: Minor violations like traffic violations (e.g., speeding or parking tickets) that usually result in fines or warnings, not imprisonment.\r\nCriminal Procedure:\r\nInvestigation and Arrest: The process begins with a law enforcement agency investigating the crime and collecting evidence. If there’s probable cause, an arrest is made.\r\nTrial Process: Includes pre-trial motions, jury selection, presenting evidence, and the sentencing phase. The accused has the right to  a fair trial and legal representation.\r\nPenalties: These vary by crime type, ranging from incarceration (long-term or short-term), probation, fines, community service, or even the death penalty in some jurisdictions.\r\nExamples:\r\nMurder: The case of Ted Bundy, where he was convicted of several murders, shows how criminal law categorizes the crime by degree (first-degree murder with death penalty sentencing in some states).\r\nTheft: The case of Bernie Madoff’s Ponzi scheme illustrates grand theft (fraud), where financial crime can result in lengthy prison terms and significant restitution.', 'Criminal law serves as a framework for maintaining order, punishing wrongdoers, and providing justice for victims.', 'z', '0', 2, '2024-12-11 14:18:52', 'Laws', 'c law.jfif', '', '', '', ''),
(21, 'The Legal Process: From Arrest to Conviction in Criminal Law', 1, 'This article will break down the entire legal process in criminal law, focusing on how a case moves from the initial arrest to conviction, providing examples of real-world cases.', 'The criminal justice system operates on specific procedures designed to ensure fairness, justice, and due process. From arrest to \r\nconviction,each stage is governed by rules meant to protect the rights of both the victim and the accused.', 'Key Stages of Criminal Procedure:\r\nArrest: The police arrest the individual based on probable cause. For example, in the George Floyd case, officers\r\nused probable cause to make an arrest, which eventually led to a trial for police misconduct.\r\nBail Hearing: Once arrested, the defendant can request bail (money paid for their release before trial) to avoid staying in jail. Bail is\r\ntypically granted for less severe crimes but denied for serious offenses like murder.\r\nArraignment: The defendant is formally charged and asked to enter a plea (guilty, not guilty, or no contest).\r\nTrial: The defendant is tried by a judge and jury (in some cases) based on the evidence presented. A famous example is the OJ \r\nSimpson trial, where the jury acquitted him of murder charges despite substantial evidence.\r\nSentencing and Appeal: After a conviction, the judge will impose a sentence. The defendant may appeal the decision if \r\nthey believe there was an error in the legal process, like in the Casey Anthony trial.\r\nPenalties:\r\nImprisonment: Felonies often lead to long prison sentences.\r\nProbation: A criminal is allowed to serve time in the community under supervision.\r\nFines: These are imposed for minor offenses.\r\nExamples:\r\nOJ Simpson: His criminal trial for the murders of Nicole Brown Simpson and Ron Goldman highlighted the complexity of the legal process.\r\nDUI Cases: Many states have stringent DUI laws that mandate penalties such as fines, imprisonment, and mandatory alcohol education.', 'Understanding the criminal process helps citizens understand their rights and how the justice system operates.', '.', '0', 2, '2024-12-11 14:28:30', 'Laws', 'l law.jfif', '', '', '', ''),
(22, 'Crime and Punishment: How Criminal Law Affects Society', 4, 'This article will analyze the broader impact of criminal law on society, including the role of deterrence, punishment, and rehabilitation in achieving justice.', 'The balance between deterring crime, punishing wrongdoers, and rehabilitating offenders is crucial in maintaining a just society.', 'Deterrence: Criminal law aims to deter individuals from committing crimes by making the consequences severe enough to discourage unlawful behavior. For example, mandatory minimum sentences for drug trafficking or three strikes laws in the U.S. have been enacted to deter repeat offenders.\r\nPunishment: Criminal punishment serves to reflect society\'s moral stance on particular offenses. Cases like Theodore Kaczynski \r\n(the Unabomber) show how punishment is a necessary tool to ensure justice is served for horrific crimes.\r\nRehabilitation: Many legal systems emphasize rehabilitating offenders to reintegrate them into society. Drug courts focus on\r\nrehabilitation rather than imprisonment for drug offenders, illustrating how rehabilitation can reduce recidivism.\r\nRestorative Justice: This is an alternative to traditional punitive measures. It focuses on repairing the harm caused by the \r\ncrime through dialogue and community service. For instance, victim-offender dialogue programs help offenders understand\r\nthe impact of their crimes.\r\nExamples:\r\nCapital Punishment: The case of Gary Ridgway, the \"Green River Killer,\" shows how capital punishment is used as a form\r\nof punishment in some states.\r\nRecidivism Reduction Programs: Programs in prisons aimed at helping offenders change their behavior to prevent future crimes.', 'Criminal law must strike a balance between deterrence, punishment, and rehabilitation to maintain a fair and just society.', '1', '5', 2, '2024-12-11 14:43:40', 'Laws', 'gk.jfif', '', '', '', NULL),
(25, 'Constitutional Law: Protecting Rights in the Criminal Justice System', 2, 'This article will discuss the constitutional protections afforded to individuals in the criminal justice system, including the right to a fair trial, protection from self-incrimination, and the right to counsel.', 'Constitutional law ensures that the government respects individual rights, especially when it comes to \r\ncriminal justice procedures. Key constitutional amendments, like the Fourth, Fifth, and Sixth Amendments,\r\nsafeguard citizens from abuses by law enforcement.', 'The Right to Due Process: The Fifth Amendment protects against double jeopardy and self-incrimination, \r\nwhile the Sixth Amendment guarantees the right to a speedy trial and the right to counsel.\r\nSearch and Seizure: The Fourth Amendment protects citizens from unreasonable searches and seizures. \r\nLandmark cases like Mapp v. Ohio (1961) illustrated the exclusionary rule, which prevents illegally obtained\r\nevidence from being used in court.\r\nEqual Protection Under the Law: The Fourteenth Amendment ensures that no one is denied equal protection\r\n under the law, which is crucial for ensuring that criminal laws are applied fairly across all races, ethnicities, and\r\nbackgrounds.\r\nExamples:\r\nMiranda v. Arizona: Established the Miranda rights that must be read to suspects before questioning.\r\nGideon v. Wainwright: Guaranteed the right to counsel for defendants who cannot afford an attorney.', 'Constitutional law plays an essential role in balancing the power of the government with the protection of individual rights', 'k', '1', 2, '2024-12-11 15:20:29', 'Laws', 'bill.jfif', '', '', '', ''),
(26, 'Civil Liberties vs. Crime: The Balance in Constitutional Law', 1, 'This article will examine the tension between civil liberties and law enforcement needs in criminal justice, exploring how constitutional law strives to protect individual freedoms while allowing effective law enforcement.', 'The balance between civil liberties and crime control is a constant challenge in the criminal justice\r\n system. For example, the USA PATRIOT Act expanded government surveillance powers, raising \r\nconcerns about the erosion of privacy.', 'Police Powers vs. Privacy: Constitutional law tries to ensure that law enforcement does not violate\r\n citizens\' rights while investigating crime. Cases like Riley v. California (2014) addressed digital \r\nprivacy and phone searches.\r\nFreedom of Speech vs. Crime Prevention: The First Amendment protects free speech, but it can\r\n clash with the need to prevent harmful speech like incitement to violence or hate speech. The \r\ncase of Brandenburg v. Ohio established the \"imminent lawless action\" test.\r\nExamples:\r\nNSA Surveillance: The debate over mass surveillance and the Fourth Amendment rights to privacy.\r\nPolice Search and Seizure: The Terry v. Ohio case, which allowed police officers to stop and frisk \r\nsuspects under certain circumstances, raised concerns about racial profiling.', 'Constitutional law must constantly evolve to balance the protection of civil liberties with the need for crime control\r\n and public safety.', 'm', '0', 2, '2024-12-11 15:37:29', 'Laws', 'vg.jfif', '', '', '', ''),
(27, 'Human Rights and Crime: Protecting Victims and Defendants', 1, 'This article will focus on the protections human rights law offers to victims and defendants, including the right to be free from torture, abuse, and discrimination in the criminal justice system.', 'Human rights law seeks to safeguard individuals from crimes, abuse, and unfair treatment, ensuring that everyone, including those \r\naccused of crimes, is treated with dignity and fairness.', 'Protections for Victims: Human rights law requires that crime victims receive adequate legal\r\n protections, including the right to participate in trials, seek restitution, and access support \r\nservices. The UN Declaration on Human Rights advocates for victim compensation and the \r\nright to seek justice.\r\nProtections for Defendants: Defendants are entitled to a fair trial, protection from torture and \r\ndegrading treatment, and the right to be presumed innocent until proven guilty.\r\nExamples:\r\nHuman Trafficking: International human rights law offers protection to trafficking victims, \r\nensuring that they are not criminalized for offenses they committed under coercion.\r\nTorture and Inhumane Treatment: The Convention Against Torture prohibits cruel and \r\nunusual punishment, safeguarding prisoners from abuse by state authorities.', ': Human rights law is essential in ensuring fairness and justice for both victims and defendants in the criminal justice system.', '1', '1', 2, '2024-12-11 16:03:06', 'Laws', 'n.jfif', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `user_id`, `email`, `bio`) VALUES
(1, 2, 'ali@gmail.com', 'a writer'),
(2, 7, 'mirianakhalil7@gmail.com', 'writter of articles');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `description`, `thumbnail`, `video`) VALUES
(1, 'Cold Cases ', 'Unsolved cases that have gone cold often with no siginficant leads for a long period ', '', ''),
(2, 'Unsolved Mysteries', 'Cases with unclear motives or strange circumstances that remain unsolved ', '', ''),
(3, 'Serial Killings ', 'cases involve multiple murders committed by the same person over time, often following a pattern.', '', ''),
(4, 'Domestic Killings', 'Cases of murder within families, often involve personal disputes.', '', ''),
(5, 'High Profile Cases', 'Cases that gain widespread media attention due to victim status, the nature of crime, or media coverage.', '', ''),
(7, 'legal and court cases ', 'overview of important legal principles, rules, and laws that govern criminal cases and how these laws are applied in real-world court cases.', '', ''),
(12, 'Laws', 'A body of law that relates to crime and punishment. Criminal \r\nlaw defines what constitutes a crime, the penalties for those \r\ncrimes, and the procedures for prosecution.', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `news_id` int(11) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `comment_text` text NOT NULL,
  `comment_date` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `section` enum('Breaking Crime News','trending_news','exclusive_videos','top_shows') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `item_id`, `user_id`, `news_id`, `username`, `comment_text`, `comment_date`, `updated_at`, `section`) VALUES
(40, NULL, 2, 1, 'ali', 'there is some mysterious events occurs in this case that allow me to refuse its a suicide.', '2025-01-07 08:41:56', NULL, 'Breaking Crime News'),
(41, 12, 2, NULL, 'ali', 'his way in killing is something special.', '2025-01-07 08:42:48', NULL, 'trending_news'),
(42, 14, 2, NULL, 'ali', 'how he did this? what he was thinking?', '2025-01-07 08:44:35', NULL, 'exclusive_videos'),
(43, NULL, 21, 20, 'ziad', 'yh', '2025-01-09 14:32:27', '2025-01-09 14:32:40', 'Breaking Crime News');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `category`, `created_at`) VALUES
(1, 'How do I create an account on Breathe News?', 'To create an account, click on the \"Sign Up\" link on the login page, fill in your details, and follow the instructions.', 'k', '2024-10-28 12:37:48'),
(2, 'Can I update my profile information?', 'Yes, you can update your username, email, password, and profile picture in the \"Manage Account\" section after logging in.', 'k', '2025-01-02 11:06:57'),
(3, 'Why can’t I log in to my account?', 'If you’re having trouble logging in, ensure your credentials are correct. If your account status is \"inactive\" or \"banned,\" you may need to contact support or create a new account.', 'l', '2025-01-02 11:10:43'),
(4, 'How can I ensure my account is secure?', 'Use a strong password, avoid sharing your login details, and regularly update your account information.', 'd', '2025-01-02 11:11:32');

-- --------------------------------------------------------

--
-- Table structure for table `job_offers`
--

CREATE TABLE `job_offers` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `summary` text NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `salary` varchar(255) DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_offers`
--

INSERT INTO `job_offers` (`id`, `title`, `summary`, `description`, `location`, `salary`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Forensic Analyst', 'We are seeking a highly skilled and detail-oriented Forensic Analyst to join our team. The successful candidate will work closely with law enforcement agencies to analyze evidence from crime scenes, providing critical insights to assist in criminal investigations.', 'Collect, preserve, and analyze physical evidence from crime scenes.Perform laboratory tests on evidence, including DNA analysis, toxicology, and trace evidence examination.Collaborate with law enforcement and legal teams to interpret findings.Prepare detailed reports and present findings in court as an expert witness.Stay updated on the latest forensic techniques and technologies.Skills Required:Proficiency in forensic analysis techniques and laboratory equipment.Strong analytical and critical thinking skills.Familiarity with criminal investigation procedures and legal standards.Excellent communication skills for report writing and courtroom testimony.Requirements:Bachelor\\\'s degree in Forensic Science, Biology, Chemistry, or a related field.Minimum 2 years of experience in a forensic lab or similar role.Certification in Forensic Science (e.g., American Board of Criminalistics) is a plus.Ability to pass a background check and security clearance.', 'Forensic Lab, State Crime Division, [UK].', '60000.00 - 80000.00$ per year', 'open', '2024-12-24 15:11:02', '2025-01-02 12:28:49'),
(4, 'Crime Scene Investigator', ' Join our dedicated team as a Crime Scene Investigator. Your primary role will be to gather evidence at crime scenes, process it in the laboratory, and assist law enforcement agencies in solving crimes.', 'Secure and preserve crime scenes.\r\nCollect evidence including fingerprints, blood samples, and other forensic materials.\r\nAnalyze physical evidence in the lab.\r\nWrite detailed reports and assist in criminal investigations.', ' Metropolitan Police Department, [NYC, USA].', '50000.00$ - 70000.00$ per year', 'open', '2025-01-02 12:31:39', '2025-01-02 12:31:39'),
(5, 'Cybersecurity Specialist', 'Protect the organization’s network infrastructure from cyber threats, implement security measures, and respond to cyber incidents.', 'Responsibilities:\r\n\r\nMonitor and defend the organization\'s network infrastructure against cyber threats.\r\nImplement security protocols and firewalls to protect sensitive data.\r\nConduct regular vulnerability assessments and penetration testing.\r\nProvide security training to employees and stakeholders.\r\nCollaborate with law enforcement agencies in case of data breaches.\r\nSkills Required:\r\nProficiency in cybersecurity tools and software.\r\nStrong understanding of network protocols and data protection.\r\nExperience with threat analysis and incident response.\r\nRequirements:\r\nBachelor’s degree in Cybersecurity, Computer Science, or related field.\r\nMinimum 3 years of experience in cybersecurity.\r\nRelevant cybersecurity certifications (e.g., CISSP, CISM).', ' London, UK', '70000.00$ to 90000.00$ per year ', 'open', '2025-01-02 13:39:16', '2025-01-02 13:39:16'),
(6, 'Forensic Pathologist', 'Perform autopsies and provide expert testimony in court to help determine the cause of death in criminal investigations.', 'Responsibilities:\r\n\r\nPerform autopsies to determine cause of death in criminal investigations.\r\nExamine tissue samples for signs of disease, injury, or poisoning.\r\nCollaborate with law enforcement and legal teams to interpret findings.\r\nTestify in court as an expert witness regarding post-mortem findings.\r\nSkills Required:\r\nExpertise in forensic pathology and autopsy procedures.\r\nAbility to work under pressure and meet strict deadlines.\r\nStrong communication skills for reporting and testimony.\r\nRequirements:\r\nMedical degree with specialization in forensic pathology.\r\nBoard certification in forensic pathology.\r\n5+ years of experience in a forensic pathology role.', 'Location: Manchester, UK', '$100,000 - $120,000 per year', 'open', '2025-01-02 13:40:36', '2025-01-02 13:40:36'),
(7, 'Crime Scene Investigator (CSI)', 'Secure crime scenes and collect evidence to assist in criminal investigations, working with forensic scientists and law enforcement.', 'Responsibilities:\r\n\r\nSecure and document crime scenes, preserving evidence for analysis.\r\nCollect physical evidence such as fingerprints, blood, and fibers.\r\nCollaborate with forensic scientists and law enforcement.\r\nPrepare detailed reports on findings for legal proceedings.\r\nSkills Required:\r\nStrong knowledge of crime scene investigation procedures.\r\nProficiency in evidence collection and documentation techniques.\r\nAbility to analyze and preserve forensic evidence.\r\nRequirements:\r\nBachelor’s degree in Forensic Science, Criminal Justice, or related field.\r\nMinimum 2 years of experience in crime scene investigation.\r\nFamiliarity with forensic photography and evidence handling.', 'Birmingham, UK', ' $50,000 - $70,000 per year', 'open', '2025-01-02 13:41:33', '2025-01-02 13:41:33'),
(8, 'Digital Forensics Expert', 'Analyze biological samples for toxic substances and support criminal investigations related to drugs and poisons.', 'Responsibilities:\r\n\r\nRecover and analyze data from digital devices (computers, phones, etc.).\r\nIdentify and preserve digital evidence from cybercrimes.\r\nCollaborate with law enforcement to investigate cybercrimes.\r\nProvide expert testimony in court regarding digital evidence.\r\nSkills Required:\r\nProficiency in digital forensics tools and software.\r\nStrong knowledge of data recovery, encryption, and cybercrime investigations.\r\nExcellent report writing and courtroom communication skills.\r\nRequirements:\r\nBachelor’s degree in Computer Science, Cybersecurity, or related field.\r\nMinimum 3 years of experience in digital forensics.\r\nCertification in Digital Forensics (e.g., EnCase, CFCE).\r\nForensic Toxicologist\r\nLocation: [UK]\r\nSalary: $60,000 - $80,000 per year\r\nResponsibilities:\r\n\r\nAnalyze biological samples for the presence of toxins, drugs, or alcohol.\r\nWork with law enforcement to identify substances involved in criminal cases.\r\nPrepare reports and testify as an expert witness in court.\r\nStay updated on developments in toxicology and forensic analysis.\r\nSkills Required:\r\nExpertise in forensic toxicology and laboratory testing.\r\nStrong understanding of toxic substances and their effects.\r\nAbility to communicate complex findings clearly.\r\nRequirements:\r\nBachelor’s degree in Forensic Toxicology, Chemistry, or related field.\r\n2+ years of experience in forensic toxicology.\r\nCertification in Forensic Toxicology is a plus.', 'Location: Glasgow, UK', 'Salary: $80,000 - $100,000 per year', 'open', '2025-01-02 13:42:28', '2025-01-02 13:42:28'),
(9, 'Criminal Investigator', 'Investigate criminal cases, gather evidence, and collaborate with law enforcement agencies to solve crimes.', 'Responsibilities:\r\n\r\nInvestigate crimes, gather evidence, and interview witnesses.\r\nAnalyze case files and evidence to build investigative reports.\r\nCoordinate with law enforcement agencies and legal teams.\r\nTestify in court regarding findings from investigations.\r\nSkills Required:\r\nStrong investigative and analytical skills.\r\nKnowledge of criminal law and procedures.\r\nExcellent communication skills for report writing and courtroom testimony.\r\nRequirements:\r\nBachelor’s degree in Criminal Justice or related field.\r\n3+ years of experience in criminal investigations.\r\nBackground in law enforcement is preferred.', 'Location: Leeds, UK', 'Salary: $50,000 - $75,000 per year', 'open', '2025-01-02 13:43:26', '2025-01-02 13:43:26'),
(10, 'Ballistics Expert', 'Analyze firearms and ammunition evidence, assisting in the identification of weapons used in criminal activities.', 'Responsibilities:\r\n\r\nAnalyze firearms, ammunition, and related evidence to identify weapon types.\r\nInvestigate shooting incidents and match bullet fragments to weapons.\r\nCollaborate with law enforcement on criminal investigations.\r\nPrepare reports and testify in court as an expert witness.\r\nSkills Required:\r\nExpertise in firearm and ballistics analysis.\r\nKnowledge of crime scene investigation techniques related to firearms.\r\nStrong communication skills for expert testimony.\r\nRequirements:\r\nDegree in Forensic Science, Ballistics, or related field.\r\n3+ years of experience in ballistics analysis.\r\nCertification in Ballistics is a plus', 'Location: Liverpool, UK', 'Salary: $70,000 - $90,000 per year', 'open', '2025-01-02 13:44:24', '2025-01-02 13:44:24'),
(11, 'Fingerprint Analyst', 'Analyze fingerprints from crime scenes to identify suspects and assist in criminal investigations.', 'Responsibilities:\r\n\r\nExamine and analyze fingerprints from crime scenes.\r\nCompare prints to databases to identify suspects.\r\nPrepare detailed reports on fingerprint analysis findings.\r\nTestify in court as an expert witness.\r\nSkills Required:\r\nProficiency in fingerprint analysis techniques and database systems.\r\nStrong attention to detail and critical thinking skills.\r\nAbility to prepare clear and concise reports.\r\nRequirements:\r\nBachelor’s degree in Forensic Science or related field.\r\nMinimum 2 years of experience in fingerprint analysis.\r\nCertification in Fingerprint Analysis (e.g., IAI certification).', 'Location: Bristol, UK', 'Salary: $55,000 - $70,000 per year', 'open', '2025-01-02 13:45:35', '2025-01-02 13:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `job_offer_stats`
--

CREATE TABLE `job_offer_stats` (
  `id` int(11) NOT NULL,
  `job_offer_id` int(11) DEFAULT NULL,
  `total_applications` int(11) DEFAULT 0,
  `total_approved` int(11) DEFAULT 0,
  `total_rejected` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_offer_stats`
--

INSERT INTO `job_offer_stats` (`id`, `job_offer_id`, `total_applications`, `total_approved`, `total_rejected`) VALUES
(13, 1, 21, 18, 2),
(14, 2, 1, 0, 0),
(15, 1, 1, 0, 0),
(16, 2, 1, 0, 0),
(17, 1, 1, 0, 0),
(18, 1, 1, 0, 0),
(19, 3, 1, 0, 0),
(20, 3, 1, 0, 0),
(21, 5, 1, 0, 0),
(22, 6, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

CREATE TABLE `milestones` (
  `id` int(11) NOT NULL,
  `milestone` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `milestones`
--

INSERT INTO `milestones` (`id`, `milestone`, `date`, `details`) VALUES
(2, 'Website Creation', '2024-11-01', 'months ago, we began our journey by creating the Breathe News platform. This milestone represents the foundation of our vision to deliver quality news to readers worldwide.'),
(3, 'Preparing for Launch', '2024-12-03', 'Currently, we are in the final stages of preparing for our official online launch. Our team is working diligently to ensure every detail is perfected, from user experience to content readiness, as we gear up to welcome our readers to Breathe News.\r\n\r\nStay tuned for our official launch date—exciting things are on the horizon!');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `inline_image_1` varchar(255) NOT NULL,
  `inline_image_2` varchar(255) NOT NULL,
  `inline_image_3` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `status` enum('published','draft','archived') NOT NULL,
  `is_breaking` tinyint(1) DEFAULT 0,
  `introduction` text NOT NULL,
  `content` text DEFAULT NULL,
  `final_thoughts` text NOT NULL,
  `author_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `description`, `date`, `category_id`, `thumbnail`, `inline_image_1`, `inline_image_2`, `inline_image_3`, `category`, `status`, `is_breaking`, `introduction`, `content`, `final_thoughts`, `author_id`) VALUES
(1, ' The Tragic Case of Rebecca Zahau - A Mystery at Spreckels Mansion', 'A deep dive into the mysterious\r\nand controversial death of Rebecca\r\nZahau at the Spreckels Mansion, \r\nexamining the official suicide \r\nruling, forensic evidence, and \r\nthe legal battle that left \r\nlingering questions about the\r\n truth behind her tragic demise.', '2024-12-06', 1, 'uploads/6318e0a573179d04ba5ce3ffcd5eb717w-c0rd-w832_h468_r4_q80.webp', 'uploads/rebecca.jfif', 'uploads/download (12)_1.jfif', 'uploads/637329292475500000.jfif', 'Cold Cases', 'published', 0, '\r\n\r\nOn July 13, 2011, Rebecca Zahau was\r\nfound dead under bizarre and tragic circumstances at the \r\nSpreckels Mansion in Coronado,\r\nCalifornia.\r\nHer death, officially ruled as a \r\nsuicide, has been surrounded by \r\nallegations of foul play and \r\nremains one of the most\r\ncontroversial cases in modern \r\nAmerican criminal history.\r\n\r\n\r\n', '\r\nRebecca was dating Jonah Shacknai,\r\na pharmaceutical mogul, \r\nat the time of her death. \r\nTwo days earlier, Jonah\'s 6-year-old\r\nson, Max Shacknai, had a\r\ndevastating fall under Rebecca’s supervision, leading to severe\r\ninjuries that would later claim\r\nhis life. The grief-stricken \r\natmosphere was further shattered\r\nwhen Rebecca was discovered hanging, bound, and nude, with a cryptic\r\nmessage painted on a nearby door.\r\n\r\nThe official investigation concluded\r\nthat Rebecca’s death was a suicide, attributing it to overwhelming guilt\r\nover Max’s accident. However, the forensic evidence raised significant questions. Blood and tape residue\r\nwere found on her body, and \r\ninjuries suggested she was \r\nassaulted before her death. \r\nThese details prompted her family\r\nto file a wrongful death lawsuit\r\nagainst Jonah’s brother,\r\nAdam Shacknai, who was present \r\nat the mansion on the night of \r\nher death.\r\n\r\nIn 2018, a civil jury held Adam \r\nShacknai responsible for Rebecca\'s \r\ndeath, awarding $5 million in \r\ndamages to her family. However, \r\nthe decision was later vacated \r\nafter a settlement. Despite \r\nmultiple reviews of the case\r\nby law enforcement, the original\r\nsuicide ruling has stood, leaving lingering doubts and a \r\ndivided public opinion.\r\n', 'Final Thoughts\r\nRebecca Zahau’s case is emblematic\r\nof the complexities and frustrations \r\nin forensic investigations. While\r\nofficial findings state suicide, \r\nthe circumstances of her death,\r\ncoupled with unanswered questions,\r\nensure this case will remain\r\na topic of debate for years to come.', 2),
(2, 'The Tragic Story of Kenneka Jenkins', 'The story of Kenneka Jenkins,\r\na 19-year-old found dead in a \r\nhotel freezer under mysterious circumstances, highlighting \r\nunanswered questions and public controversy.', '2024-12-06', 1, 'uploads/keneeka.jenkins.crowne-plaza-freezer_wide-f0e0ff628ca8e55e8bdd68cc04fbd1cb2e0b9ee2_1.webp', 'uploads/download (9)_1.jfif', 'uploads/download (10)_1.jfif', 'uploads/download (12)_2.jfif', 'Cold Cases', 'published', 0, 'Kenneka Jenkins, a 19-year-old \r\nfrom Chicago, went to a party at\r\nthe Crowne Plaza Chicago O\'Hare\r\nHotel on September 8, 2017. What\r\nseemed like an ordinary night of\r\nfun with friends turned into a heartbreaking tragedy when she was\r\nfound dead inside a walk-in freezer \r\nat the hotel. Her case sparked\r\nwidespread media attention, \r\npublic outrage, and numerous \r\nconspiracy theories that continue \r\nto raise questions about the \r\ncircumstances of her death.', 'Kenneka had initially gone out \r\nwith friends to a bowling alley,\r\nbut plans changed when they \r\nattended a party on the hotel\'s\r\nninth floor. Around 1 a.m., she\r\nwas seen entering the hotel and\r\nlater separated from her friends.\r\nThey later claimed they left her\r\nto retrieve her belongings, but\r\nwhen they returned, she was \r\nnowhere to be found. Over the \r\nnext 24 hours, the hotel and \r\nher family conducted an \r\nextensive search, but it wasn’t\r\nuntil surveillance footage \r\nemerged showing Kenneka \r\nstumbling through the hotel’s\r\nhallways that the last moments\r\nof her life became clearer.\r\n\r\nThe footage revealed Kenneka \r\nappearing intoxicated and \r\ndisoriented, ultimately heading\r\ntowards a kitchen area under \r\nrenovation, where she entered\r\na walk-in freezer. Her body was discovered the next day, and \r\nthe autopsy revealed that her\r\ncause of death was hypothermia, complicated by alcohol and a\r\nprescription drug she had not\r\nbeen prescribed. Although the \r\ncoroner ruled out foul play, \r\nquestions lingered about how an intoxicated individual could have accessed the freezer, leading\r\nher family to question whether \r\nnegligence by the hotel \r\ncontributed to her death', 'The case of Kenneka Jenkins \r\nremains tragic and controversial,\r\nwith many unresolved questions\r\nsurrounding her final hours. \r\nWhile the official ruling of \r\naccidental death has been \r\naccepted by authorities, \r\nthe family\'s persistence in \r\nseeking justice highlights \r\nconcerns over accountability\r\n and transparency. Her story \r\nserves as a reminder of the\r\n importance of safeguarding\r\n vulnerable individuals in\r\n public spaces.', 2),
(3, 'The Mysterious Death of Elisa Lam', 'This article explores the strange\r\nand mysterious death of Elisa Lam,\r\na 21-year-old student whose body \r\nwas found in a water tank at the\r\nCecil Hotel in 2013. Despite the\r\nofficial ruling of accidental\r\ndrowning, the case remains shrouded\r\nin mystery, leading to widespread speculation and intrigue.', '2024-12-06', 1, 'uploads/elisa 1.jfif', 'uploads/download (18).jfif', 'uploads/images (3).jfif', 'uploads/images (2).jfif', 'Cold Cases', 'published', 0, 'Elisa Lam’s death remains one \r\nof the most mysterious and widely\r\ndiscussed cases of the 21st century.\r\nIn 2013, the 21-year-old Canadian\r\nstudent was staying at the Cecil\r\nHotel in Los Angeles when she\r\ndisappeared under strange\r\ncircumstances. Her body was later\r\nfound in a water tank on the \r\nhotel’s rooftop, sparking global\r\nattention and raising many \r\nquestions about her fate.', 'Elisa Lam was last seen on January 31, 2013, when she failed to \r\ncontact her parents, who she\r\nregularly communicated with\r\nduring her travels. Hotel staff\r\nreported her being alone when \r\nshe was last seen. A week later,\r\nthe LAPD released disturbing \r\nsurveillance footage of Elisa \r\nin an elevator, acting \r\nerratically: she pressed multiple buttons, peered out of the \r\nelevator, and made strange gestures.\r\nThis video quickly went viral and\r\nsparked intense speculation about\r\nher mental state and what might \r\nhave been happening.\r\n\r\nOn February 19, 2013, Elisa’s body\r\nwas discovered in a rooftop water \r\ntank after guests complained about discolored water. Her body was\r\nfound nude, with her belongings\r\nfloating nearby. Autopsy reports\r\nruled her death as accidental \r\ndrowning, with bipolar disorder\r\nas a key factor. However, the circumstances of how she managed\r\nto access the locked rooftop \r\nand climb into the tank without triggering alarms have fueled\r\nconspiracy theories about foul \r\nplay or supernatural involvement,\r\ngiven the hotel\'s dark history.\r\n\r\nDespite the investigation, many \r\naspects of Elisa’s case remain unresolved. The hotel’s notorious reputation, including links to\r\nserial killers and unexplained \r\ndeaths, has only added to the \r\nintrigue surrounding Elisa\'s\r\ndeath, leading to enduring \r\nfascination with her story.', 'Elisa Lam’s case serves as a \r\nreminder of the complexities\r\nof mental health and how \r\nunexplained circumstances can \r\nfuel public curiosity. While\r\nher death was ruled accidental,\r\nthe unanswered questions continue\r\nto captivate people\'s imaginations\r\nand highlight the importance of\r\napproaching mental health with\r\nempathy and understanding.\r\n\r\n', 2),
(5, ' The Tragic Disappearance of Kris Kremers and Lisanne Froon', 'In April 2014, two Dutch tourists,\r\nKris Kremers and Lisanne Froon,\r\nvanished while hiking in Panama\'s\r\ndense jungle. Despite a massive \r\nsearch, their disappearance remains unsolved, with mysterious evidence\r\nsuch as eerie photos and a recovered backpack fueling speculation about\r\ntheir fate.', '2024-12-06', 2, 'uploads/k1.jfif', 'uploads/download (2).jfif', 'uploads/download (3).jfif', 'uploads/download (4).jfif', 'Unsolved Mysteries', 'published', 0, 'In April 2014, Kris Kremers and \r\nLisanne Froon, two Dutch tourists,\r\nset out for an adventure in the lush jungles of Panama.\r\nWhat began as a picturesque hike\r\non the Pianista Trail turned into\r\na chilling mystery that captured \r\nthe world’s attention.\r\nTheir disappearance, and the cryptic evidence left \r\nbehind, remains one of the most \r\nhaunting unsolved cases of recent \r\ntimes.', 'Kris and Lisanne arrived in Boquete, Panama, with hopes of combining \r\nvolunteer work and exploration. \r\nOn April 1, they embarked on the\r\nPianista Trail, a popular hike, but failed to return that evening. \r\nWhen their host family noticed \r\ntheir absence, a large-scale \r\nsearch operation was launched.\r\n\r\nTen weeks after their disappearance,\r\na local woman found Lisanne’s \r\nbackpack along a riverbank miles\r\nfrom the trail. It contained \r\nessentials like their phones, \r\na camera, and cash, all in pristine condition despite the humid \r\nconditions. Investigators uncovered disturbing details: the phones\r\nhad attempted multiple emergency \r\ncalls, and their camera held \r\nseveral puzzling images, \r\nincluding cheerful selfies \r\nand, later, photos of darkness \r\nand the jungle, suggesting they \r\nwere lost.\r\n\r\nMonths later, skeletal remains \r\nwere discovered downstream, \r\nincluding a boot containing \r\nLisanne’s foot and a pelvic \r\nbone identified as Kris\'s. \r\nForensic analysis suggested \r\nLisanne may have fallen to \r\nher death, while Kris\'s remains\r\n showed possible environmental\r\n exposure. However, the scattered \r\nremains and eerie photos fueled speculation about foul play', 'The tragic story of Kris Kremers \r\nand Lisanne Froon remains shrouded\r\nin mystery. Their fate serves as\r\na somber reminder of the risks\r\nassociated with remote exploration\r\nand the enduring need for caution\r\nand preparedness in uncharted terrains.', 2),
(6, 'Jeffrey Dahmer: The \"Milwaukee Cannibal\"', '\r\nJeffrey Dahmer, known as the \"Milwaukee Cannibal,\" was an \r\nAmerican serial killer who murdered 17 young men between \r\n1978 and 1991. His crimes involved gruesome acts of necrophilia,\r\n dismemberment, and cannibalism. Dahmer lured his victims to\r\n his apartment, where he would drug, kill, and then dispose of \r\ntheir bodies in disturbing ways. His arrest in 1991 brought to \r\nlight the horrors he had committed over more than a decade.\r\n Dahmer was sentenced to life imprisonment but was killed by\r\n a fellow inmate in 1994. His chilling case remains one of the\r\n most infamous in American criminal history.', '2024-12-06', 3, 'uploads/download (12)_3.jfif', 'uploads/download (13).jfif', 'uploads/download (14).jfif', 'uploads/download (15).jfif', 'Serial Killings ', 'published', 0, 'Jeffrey Lionel Dahmer was born on May 21, 1960, in\r\nMilwaukee, Wisconsin. His childhood \r\nwas marked by isolation and \r\nbehavioral issues, which were\r\ncompounded by his parents\' tumultuous \r\nmarriage and eventual divorce.\r\nDahmer showed an early \r\nfascination with dead animals and\r\nwas known to dissect them, an \r\nunsettling precursor to his later\r\ncrimes. His behavior gradually \r\nescalated, and by the time he was\r\na teenager, Dahmer was beginning to struggle with his sexual identity\r\nand violent impulses.', 'Dahmer\'s first known victim, Steven Hicks, was murdered in\r\n 1978 when Dahmer was just 18 years old. Over the \r\nfollowing decade, his killing spree continued, with Dahmer \r\nluring men to his apartment, where he would drug, sexually \r\nassault, and then kill them. After the murders, he engaged \r\nin macabre rituals, including dismembering the bodies, \r\nkeeping body parts as trophies, and even consuming parts\r\n of his victims.\r\n\r\nThe police were unaware of Dahmer’s activities for several years,\r\n as he successfully evaded capture due to his ability to conceal his\r\n crimes. His fascination with controlling his victims extended \r\nbeyond murder—Dahmer sought to create \"zombie-like\" beings\r\n by injecting their brains with acid, rendering them unconscious\r\n before killing them.\r\nArrest and Conviction:\r\nDahmer\'s downfall came in 1991 when one of his intended victims,\r\nTracy Edwards, managed to escape from his apartment and flagged\r\ndown police officers. Edwards led the authorities back to the \r\napartment, where they discovered photographs of dismembered\r\n bodies and human remains. Dahmer was arrested on July 22, 1991,\r\n and his subsequent confession detailed the full extent of his horrific\r\n crimes.\r\n\r\nIn 1992, Dahmer was convicted of 15 murders and sentenced\r\n to 15 consecutive life terms in prison without the possibility \r\nof parole. During his trial, Dahmer expressed remorse for his \r\nactions, but his crimes were too horrific to comprehend.\r\n', 'Jeffrey Dahmer\'s crimes are a tragic reminder of the \r\ndarkness that can lie beneath the surface of seemingly\r\n ordinary lives. His disturbing acts of violence, combined\r\n with his disturbing psychological profile, have led to\r\n countless debates about the nature of evil and the\r\n motivations behind serial killing. Dahmer’s case remains\r\n one of the most chilling in criminal history, as it \r\nunderscores the capacity for brutality and the devastating\r\n consequences of unchecked mental illness.\r\n\r\nDespite his death in prison in 1994, Dahmer\'s legacy \r\ncontinues to haunt the public imagination, with documentaries, \r\nbooks, and films exploring his life\r\nand crimes. His story serves as both\r\na warning and a profound source of fascination, ensuring his \r\nplace as one of the most infamous criminals in American history.', 2),
(17, 'The Murder of Laci Peterson (2002)', 'A detailed account of the high-profile case of Laci \r\nPeterson’s tragic death, highlighting the investigation, \r\nScott Peterson’s conviction, and the public outcry \r\nsurrounding the case.', '2024-12-07', 4, 'uploads/download.jfif', 'uploads/download (2)_1.jfif', 'uploads/download (3)_1.jfif', 'uploads/download (4)_1.jfif', 'Domestic Killings', 'published', 0, 'Laci Peterson’s murder became one of the most high-profile\r\n criminal cases in the United States. Her tragic death in 2002\r\n not only shocked the nation but also shed light on the \r\ncomplexities of domestic violence, manipulation, and\r\n deception. Scott Peterson, her husband, was later \r\nconvicted of her murder, as well as that of their unborn child,\r\n Conner Peterson', 'Laci Denise Peterson was a 27-year-old woman living in\r\nModesto, California, with her husband, Scott Peterson. \r\nLaci was eight months pregnant with their son, Conner,\r\n when she went missing on Christmas Eve, 2002. \r\nHer disappearance set off a massive search that captured\r\n nationwide attention. Scott Peterson told authorities \r\nthat he had last seen his wife leaving their home in the\r\n afternoon, after which she vanished without a trace.\r\n\r\nThe investigation into Laci’s disappearance grew more \r\nsuspicious when it was revealed that Scott Peterson had \r\nbeen acting strangely. He was seen shopping and golfing\r\n after his wife’s disappearance and had been having an \r\naffair with another woman, Amber Frey. Despite his claims of innocence, Scott’s behavior was increasingly deemed inconsistent with someone who had just lost their wife and unborn child.\r\n\r\nAs the search for Laci continued, police found her body in the San Francisco Bay in April 2003, about four months after she was reported missing. Conner’s body was also found, and the authorities concluded that both had been murdered. Evidence suggested that Scott had been involved in their deaths, and he was arrested and charged with two counts of murder.\r\n\r\nScott Peterson’s trial, which began in 2004, garnered intense media coverage. The prosecution argued that Peterson had killed his wife to be free of her and their impending child, as he was having an affair with Frey. On the other hand, the defense claimed that there was insufficient evidence to prove Peterson’s guilt. However, Peterson’s behavior, including his lies and lack of remorse, played a key role in his conviction. In November 2004, Peterson was found guilty of two counts of murder: first-degree murder for Laci’s death and second-degree murder for Conner’s.\r\n\r\nIn 2005, Peterson was sentenced to death, although the case remains controversial, especially with ongoing appeals. Peterson\'s trial and conviction raised important questions about domestic violence, manipulation, and the dark side of seemingly perfect relationships.', 'The murder of Laci Peterson shocked the nation, especially given\r\n the circumstances surrounding her disappearance and death.\r\n It highlighted the possibility of domestic violence hidden \r\nbeneath the surface of normal family life and led to a public\r\n discussion about the dangers of controlling and deceitful\r\n behavior in intimate relationships. Despite the conviction,\r\n Peterson’s case continues to evoke debate, making it a \r\nhaunting example of the complexities within domestic tragedies.', 2),
(18, 'The Murder of Andrea Yates', 'An exploration of the devastating case of Andrea Yates,\r\n who drowned her five children in 2001, shedding light \r\non the tragic effects of untreated postpartum psychosis\r\n and mental illness.', '2024-12-07', 4, 'uploads/download (5).jfif', 'uploads/download (6).jfif', 'uploads/download (7).jfif', 'uploads/download (8).jfif', 'Domestic Killings', 'published', 0, ' Andrea Yates’ case is a heart-wrenching example of how\r\n untreated mental illness can spiral out of control, resulting \r\nin tragic consequences. Her story has sparked discussions\r\n on mental health care and the responsibilities of those \r\naround individuals suffering from postpartum depression \r\nand psychosis.', 'Andrea Yates was a 36-year-old mother who had suffered\r\n from severe postpartum depression and psychosis\r\n following the births of her children. Over the years,\r\n Yates had been hospitalized multiple times and had\r\n even been prescribed psychiatric medications. \r\nDespite her condition, she and her husband, Russell Yates,\r\nhad continued to have children, with Andrea giving \r\nbirth to five children in a span of seven years.\r\n\r\nOn June 20, 2001, Andrea Yates drowned her five children—Noah, John, Paul, Luke, and Mary—in the bathtub of their home. At the time, Andrea believed that she was saving her children from eternal damnation. She thought that by killing them, she would spare them from going to hell, as she had been convinced by her delusions that they were beyond saving. After committing the murders, she called her husband, who was at work, and told him what she had done. He immediately rushed home and found the children’s bodies.\r\n\r\nAndrea was arrested and charged with capital murder. Her trial began in 2002, and her defense argued that she was not guilty by reason of insanity due to her severe mental illness. The prosecution, however, claimed that Andrea was in control of her actions, and she was initially convicted of murder in 2002. She was sentenced to life in prison.\r\n\r\nIn 2006, however, Andrea\'s conviction was overturned in a retrial. The new trial highlighted the lack of proper mental health care, and experts argued that she was not in her right mind at the time of the killings. The retrial also brought attention to the role of postpartum psychosis, a rare but severe condition that can result in hallucinations, delusions, and even violent behavior. In 2006, Andrea was found not guilty by reason of insanity, and she was committed to a psychiatric facility instead of prison.', 'The murder of Andrea Yates\' children remains one of the \r\nmost tragic and disturbing events in recent American \r\nhistory. Her case exposed the dangers of untreated\r\n mental illness, particularly postpartum depression \r\nand psychosis. It also led to a national conversation\r\n about mental health care and the responsibility of \r\nthose around individuals suffering from these conditions.\r\n Andrea Yates\' story is a reminder of how severe mental \r\nillness can distort reality and lead to devastating \r\nconsequences if not properly addressed.', 2),
(19, 'The Amanda Knox Case: A Fight for Justice', 'Amanda Knox is an American woman who became widely \r\nknown after being accused and later acquitted of the 2007\r\n murder of her British roommate, Meredith Kercher, in Perugia,\r\n Italy.', '2024-12-07', 5, 'uploads/amanda1.jfif', 'uploads/amanda2.jfif', 'uploads/amanda3.jfif', 'uploads/d.jfif', 'High Profile Cases', 'published', 0, 'The murder of British student Meredith Kercher in Perugia,\r\nItaly, in 2007 left the world stunned. But it was the case \r\nagainst her American roommate, Amanda Knox, that \r\ncaptivated the media for years. Accused of brutally murdering\r\n Kercher, Knox\'s trial and eventual acquittal raised significant\r\n questions about justice, media influence, and international \r\nlegal systems.', 'On November 1, 2007, Meredith Kercher, a 21-year-old British \r\nexchange student, was found murdered in her shared \r\napartment in Perugia, Italy. The police investigation quickly \r\nturned towards her American roommate, Amanda Knox, who,\r\n along with her then-boyfriend Raffaele Sollecito, was accused\r\n of committing the crime.\r\n\r\nKnox’s behavior after the murder was scrutinized, with her actions\r\n — including socializing with friends and providing inconsistent\r\n statements — seen as suspicious. However, critics argued that the\r\n investigation was flawed, with police focusing too heavily on Knox\r\n and Sollecito, even disregarding potential evidence pointing to\r\n other suspects. A controversial forensic report, based on unreliable \r\nevidence, further fueled the belief that Knox was guilty.\r\n\r\nIn 2009, Knox and Sollecito were convicted of murder and sexual\r\n assault, despite the lack of concrete evidence. However, their \r\nconviction was overturned in 2011, and the Italian Supreme Court\r\n ordered a retrial, which led to their definitive acquittal in 2014.\r\n\r\nThe case raised concerns about the fairness of the investigation and \r\ntrial process in Italy. Knox, who had spent four years in prison, finally\r\n returned to the U.S., where the media\'s portrayal of her as a \"cold\" and\r\n \"promiscuous\" woman continued to influence public opinion, despite her acquittal.', 'The Amanda Knox case is a reminder of the complexities involved\r\n in high-profile legal battles, where public opinion can sometimes\r\n overshadow the principles of justice. The controversy surrounding\r\n her trial and eventual exoneration serves as a cautionary tale about\r\n rushing to judgment in cases that capture the media’s attention.', 2),
(20, 'The Jodi Arias Case: A Grisly Murder and a Highly Publicized Trial', 'Jodi Arias is an American woman convicted of the 2008 murder\r\n of her ex-boyfriend, Travis Alexander, in Mesa, Arizona. \r\nArias initially denied involvement but later admitted to killing \r\nAlexander, claiming it was in self-defense during a violent \r\naltercation.', '2024-12-07', 5, 'uploads/jordi.jfif', 'uploads/jordi2.jfif', 'uploads/jord3.jfif', 'uploads/trvis.jfif', 'High Profile Cases', 'published', 0, 'The brutal murder of Travis Alexander in 2008 led to one of \r\nthe most sensational trials of the 21st century, with Jodi Arias\r\n at the center. Her shifting stories, shocking testimony, and the\r\n brutality of the crime itself made the case a media spectacle. \r\nThe trial and its aftermath left the public divided, sparking debates\r\n about domestic violence, justice, and media manipulation.', 'On June 4, 2008, Travis Alexander, a 30-year-old motivational\r\n speaker, was found brutally murdered in his home in Mesa, \r\nArizona. His ex-girlfriend, Jodi Arias, became the prime suspect,\r\n and the investigation revealed a complex and toxic \r\nrelationship between the two.\r\n\r\nArias initially denied her involvement in the crime, but after intensive\r\nquestioning, she admitted to killing Alexander. She claimed it was in\r\nself-defense during a violent altercation. However, as the trial \r\nprogressed, Arias\'s story changed multiple times, leading \r\nprosecutors to argue that the murder was premeditated and committed\r\n out of jealousy and revenge.\r\n\r\nThe brutal nature of the crime — Alexander was stabbed 27 times,\r\nshot in the face, and nearly decapitated — shocked the public. \r\nArias\'s testimony and demeanor during the trial became a spectacle, \r\nwith media outlets covering her every word and action. Her changing\r\naccounts and emotional shifts on the stand left many wondering if she\r\n was truly guilty or the victim of an abusive relationship.\r\n\r\nAfter a lengthy trial, Arias was convicted of first-degree murder in \r\nMay 2013. She was sentenced to life in prison without the possibility\r\n of parole, and the case remains one of the most notorious criminal \r\ntrials in recent history.', 'The Jodi Arias case underscored the complexities of domestic violence,\r\n manipulation, and the sensationalism that can accompany high-profile\r\n trials. Arias’s emotional testimony and the brutal nature of the murder\r\n left an indelible mark on the public’s perception, sparking debate over\r\n the intersection of crime, punishment, and media coverage.', 2),
(21, 'The Derrick Bird Mass Shooting: A Tragic Event and its Aftermath', 'Derrick Bird’s shooting spree in Cumbria, England, in June \r\n2010 shocked the nation. This article explores the details \r\nof the event, the aftermath, and the implications for\r\nmental health and gun control.', '2024-12-08', 7, 'uploads/bird.jfif', 'uploads/bird1.jfif', 'uploads/bird2.jfif', 'uploads/bird3.jfif', NULL, 'published', 0, ' On June 2, 2010, Derrick Bird, a 52-year-old taxi driver,\r\ncarried out one of the most tragic and senseless mass\r\nshootings in British history. Over a span of several hours,\r\nBird killed 12 people and injured 11 others across the \r\nrural areas of Cumbria, England. The shooting spree, \r\nwhich appeared to be driven by personal grievances, \r\nshocked the nation and left a deep scar in the community.\r\n The case, marked by its randomness and brutality, also\r\n raised serious questions about mental health, gun\r\n control, and the social factors leading to such violent acts.\r\n This article examines the events of the shooting, its \r\naftermath, and the ongoing discussions about mental \r\nhealth and firearms legislation in the UK.', 'The events of June 2, 2010, were both shocking and bewildering.\r\n Derrick Bird embarked on a violent journey across the Cumbrian\r\n countryside, shooting at random victims. He killed 12 people,\r\n injuring 11 others before taking his own life. While there was no\r\n clear connection between the victims, many speculated that \r\nBird\'s attack was motivated by personal grievances. He had recently\r\n been involved in disputes related to family and his job. At the time \r\nof the killings, Bird’s mental health had been deteriorating. In the \r\naftermath of the tragedy, questions were raised about the\r\n accessibility of firearms, mental health care, and whether the\r\n UK’s gun laws were stringent enough to prevent such a disaster.', 'The Derrick Bird shooting spree serves as a tragic reminder of the\r\n devastating consequences of untreated mental illness and the \r\ncomplexities surrounding gun control. While Bird’s actions were\r\n largely attributed to personal grievances and mental health \r\nissues, the broader implications of this case have had a lasting\r\n impact on the UK’s gun laws and mental health policies. Though\r\n the gunman ended his own life, leaving many questions \r\nunanswered, the event has led to increased awareness and \r\ndebate surrounding mental health care and the need for better\r\n preventive measures to avoid such tragedies in the future.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `news_tags`
--

CREATE TABLE `news_tags` (
  `news_id` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `article_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_tags`
--

INSERT INTO `news_tags` (`news_id`, `tag`, `article_id`) VALUES
(1, '#rebecca #max', 1),
(2, '#kennicka #killed', 3),
(3, '#elisa #disapperance #mental_issues', 4),
(4, '#liz #kris #forest #disappear', 10),
(6, '#faye #killed ', 11),
(17, '#peterson #killed', NULL),
(18, '#mental_health #killed', NULL),
(19, '#personal_case #ungulity', NULL),
(20, '#travis #jodi #media', NULL),
(21, '#cult #religion', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE `polls` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `polls`
--

INSERT INTO `polls` (`id`, `question`, `status`, `created_at`, `updated_at`) VALUES
(5, '\"Do you believe Rebecca Zahau\'s death was a result of foul play, or was it suicide?\"', 'Active', '2024-12-24 15:03:07', '2024-12-24 15:04:08'),
(6, 'Do you think Elisa Lim\'s case was handled fairly by the authorities?', 'Active', '2025-01-02 11:29:44', '2025-01-02 11:29:44'),
(7, 'What do you think about the public\'s response to Kenice Jenkz\'s involvement in the crime?', 'Active', '2025-01-02 11:30:43', '2025-01-02 11:30:43'),
(8, 'What is your opinion on the evidence found during the search for Kris Kremers and Lisanne Froon?', 'Active', '2025-01-02 11:33:05', '2025-01-02 11:33:05'),
(9, ' How do you view the psychological theories surrounding Ted Bundy’s actions?', 'Active', '2025-01-02 11:34:38', '2025-01-02 11:34:38'),
(10, ' Do you think Chris Watts received a fair sentence for his crimes?', 'Active', '2025-01-02 11:36:05', '2025-01-02 11:36:05'),
(11, ' Do you support the use of the death penalty in criminal cases?', 'Active', '2025-01-02 11:38:10', '2025-01-02 11:38:10'),
(12, 'Do you believe that police use of force is properly regulated in your country?  ', 'Active', '2025-01-02 11:38:57', '2025-01-02 11:38:57'),
(13, 'Do you believe in the rehabilitation of prisoners, or should they focus solely on punishment?', 'Active', '2025-01-02 11:40:12', '2025-01-02 11:40:12'),
(14, 'Do you think current civil rights laws adequately protect minorities in your country?', 'Active', '2025-01-02 11:41:33', '2025-01-02 11:41:33'),
(15, 'Do you think immigration laws in your country are fair and just?', 'Active', '2025-01-02 11:43:17', '2025-01-02 11:43:17');

-- --------------------------------------------------------

--
-- Table structure for table `poll_options`
--

CREATE TABLE `poll_options` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) DEFAULT NULL,
  `option_text` varchar(255) DEFAULT NULL,
  `votes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poll_options`
--

INSERT INTO `poll_options` (`id`, `poll_id`, `option_text`, `votes`) VALUES
(28, 5, 'It was foul play; the evidence suggests murder.', 1),
(29, 5, 'It was suicide; the investigation seems accurate.', 2),
(30, 5, 'I\'m undecided; the case remains too ambiguous.', 0),
(32, 5, '', 0),
(33, 6, 'Yes, the investigation was thorough.', 0),
(34, 6, 'No, the authorities mishandled the case.', 0),
(35, 6, 'The case is still under investigation, so it\'s too early to tell.', 0),
(36, 6, 'I’m not familiar with the case.', 0),
(37, 7, ' The public has been too harsh.', 2),
(38, 7, 'The public’s reaction is justified.', 1),
(39, 7, 'The public doesn’t have enough information to judge.', 0),
(40, 7, ' I’m not familiar with the case.', 1),
(41, 8, 'The evidence strongly suggests foul play', 1),
(42, 8, ' The evidence is inconclusive and doesn\'t lead to a clear answer', 0),
(43, 8, 'The evidence is misleading and doesn’t explain the disappearance.', 0),
(44, 8, ' I’m not familiar with the evidence presented.', 0),
(45, 9, 'He was a textbook example of a psychopathic killer with no remorse.', 0),
(46, 9, 'His actions might have been the result of a mix of mental illness and environmental factors.', 0),
(47, 9, 'He was simply a criminal who exploited his charm to deceive people. ', 0),
(48, 9, 'I’m not sure about the psychological aspects of his case.', 0),
(49, 10, 'Yes, the sentencing was appropriate given the severity of his actions. ', 0),
(50, 10, 'No, he should have received a harsher sentence.', 0),
(51, 10, 'The death penalty should have been an option.', 0),
(52, 10, 'I’m not sure what the appropriate sentence would have been.', 0),
(53, 11, 'Yes, it serves as a deterrent and justice for the victims.', 0),
(54, 11, 'No, the death penalty should be abolished.', 0),
(55, 11, 'It should only be used in extreme cases of heinous crimes. ', 0),
(56, 11, 'I’m not sure about my stance on this issue.', 0),
(57, 12, 'A) Yes, there are clear and fair regulations in place.', 0),
(58, 12, 'B) No, police use of force is too excessive and unregulated', 0),
(59, 12, ' C) The regulations exist but are not enforced properly.', 0),
(60, 12, 'D) I’m not familiar with the regulations in my country.', 0),
(61, 13, 'A) Rehabilitation should be the focus to help offenders reintegrate into society. ', 0),
(62, 13, 'B) Punishment should be the primary focus; rehabilitation is not effective.', 0),
(63, 13, ' C) A balance of both rehabilitation and punishment is necessary.', 0),
(64, 13, 'D) I’m not sure whether rehabilitation is effective in the criminal justice system.', 0),
(65, 14, 'A) Yes, civil rights laws are sufficient and well-enforced.', 0),
(66, 14, 'B) No, there are still gaps in the protection of minorities’ rights.', 0),
(67, 14, 'C) There are protections, but they need to be strengthened and expanded.', 0),
(68, 14, ' D) I’m not familiar with civil rights laws in my country.', 0),
(69, 15, 'A) Yes, the laws are fair and create a balanced approach.', 0),
(70, 15, 'B) No, the laws are too restrictive and need reform.', 0),
(71, 15, 'C) The laws need to be more focused on humanitarian concerns.', 0),
(72, 15, 'D) I’m not sure if the current immigration laws are fair.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `poll_votes`
--

CREATE TABLE `poll_votes` (
  `id` int(11) NOT NULL,
  `poll_id` int(11) DEFAULT NULL,
  `option_id` int(11) DEFAULT NULL,
  `voted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `poll_votes`
--

INSERT INTO `poll_votes` (`id`, `poll_id`, `option_id`, `voted_at`, `user_id`) VALUES
(40, 5, 29, '2024-12-24 15:03:41', 1),
(41, 5, 29, '2024-12-24 16:42:12', 2),
(42, 7, 37, '2025-01-02 14:21:45', 2),
(43, 7, 38, '2025-01-03 10:15:10', 2),
(44, 8, 41, '2025-01-07 06:50:54', 2),
(45, 7, 40, '2025-01-09 02:06:33', 2),
(46, 5, 28, '2025-01-09 11:07:23', 2),
(47, 7, 37, '2025-01-09 12:36:15', 21);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','short_answer','true_false') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `question_type`) VALUES
(5, 3, 'What was the manner of Rebecca Zahau\'s death, according to the initial investigation?', 'multiple_choice'),
(6, 3, 'Rebecca Zahau\'s body was found at the Spreckels Mansion in Coronado, California.', 'true_false'),
(7, 3, 'What was the controversial method by which Rebecca Zahau was found dead?', 'short_answer'),
(8, 3, 'Who was the key person associated with Rebecca Zahau\'s death and was the subject of much media speculation?', 'multiple_choice'),
(9, 3, 'Rebecca Zahau was found hanging from a balcony at the mansion.', 'true_false'),
(10, 3, 'What was the role of Max Shacknai in the events leading up to Rebecca Zahau\'s death?', 'short_answer'),
(12, 3, 'The death of Rebecca Zahau was ruled a suicide, though there were many who believed otherwise.', 'true_false'),
(13, 3, ': What was one of the most controversial aspects of Rebecca Zahau\'s death investigation?', 'multiple_choice'),
(14, 3, 'In what way did Rebecca Zahau’s family challenge the initial ruling of her death?', 'short_answer'),
(18, 4, 'What was the mysterious event associated with Elisa Lam in 2013?', 'multiple_choice'),
(19, 4, 'Which hotel is connected to Elisa Lam\'s case?', 'multiple_choice'),
(20, 4, 'What unusual behavior was observed in the Elisa Lam elevator footage?', 'multiple_choice'),
(21, 4, 'What was Kenica Jencans known for?', 'multiple_choice'),
(22, 4, 'Elisa Lam was found alive after the incident at the Cecil Hotel.', 'true_false'),
(23, 4, 'The Cecil Hotel has a history of mysterious deaths and criminal activities.', 'true_false'),
(24, 4, 'Kenica Jencans is a verified historical figure associated with urban legends.', 'true_false'),
(25, 4, 'Describe the impact of Elisa Lam’s case on the popularity of the Cecil Hotel.\r\n(Sample Answer: Elisa Lam\'s case brought widespread attention to the Cecil Hotel, highlighting its history of mysterious incidents and increasing its notoriety as a site for true crime enthusiasts.)', 'short_answer'),
(26, 4, 'What do you think makes cases like Elisa Lam’s intriguing for crime enthusiasts?\r\n(Sample Answer: Cases like Elisa Lam\'s are intriguing due to the unexplained nature of events, the involvement of video evidence, and the psychological and supernatural theories that arise.)', 'short_answer'),
(28, 5, 'In which country did Kris Kremers and Lisanne Froon disappear?', 'multiple_choice'),
(29, 5, 'What were Kris Kremers and Lisanne Froon doing before they disappeared?', 'multiple_choice'),
(30, 5, 'What was found that led to the investigation of their disappearance?', 'multiple_choice'),
(31, 5, 'The remains of Kris Kremers and Lisanne Froon were found together, fully intact.', 'true_false'),
(32, 5, 'The camera found had several photos taken after Kris and Lisanne went missing.', 'true_false'),
(33, 5, 'What theories surround the disappearance of Kris Kremers and Lisanne Froon?\r\n(Sample Answer: Several theories suggest they got lost while hiking, possibly due to poor weather or injury. There are also theories about foul play, an animal attack, or a mysterious encounter in the jungle.)', 'short_answer'),
(34, 6, 'What was Ted Bundy’s primary method of luring victims?', 'multiple_choice'),
(35, 6, 'Which of the following states was NOT one of the places where Ted Bundy committed his crimes?', 'multiple_choice'),
(36, 6, 'What year was Ted Bundy first arrested?', 'multiple_choice'),
(37, 6, 'How many victims is Ted Bundy believed to have murdered?', 'multiple_choice'),
(38, 6, 'Ted Bundy was only convicted for the murder of one victim.', 'true_false'),
(39, 6, 'Ted Bundy managed to escape from prison twice.', 'true_false'),
(40, 7, 'What year did Chris Watts murder his wife Shanann and their two daughters?', 'multiple_choice'),
(41, 7, 'Where did Chris Watts commit the murders?', 'multiple_choice'),
(42, 7, 'How did Chris Watts initially explain the disappearance of his wife and daughters?', 'multiple_choice'),
(43, 7, 'Chris Watts was convicted of first-degree murder for the death of his wife but not for the deaths of his daughters.', 'true_false'),
(44, 7, 'What were the motives behind Chris Watts\' crimes according to his confession?', 'short_answer'),
(46, 8, 'Which of the following is an example of a \"white-collar crime\"?', 'multiple_choice'),
(47, 8, 'What is the legal term for a crime that is punishable by death or imprisonment for more than one year?', 'multiple_choice'),
(48, 8, 'What is the primary purpose of criminal law?', 'multiple_choice'),
(49, 8, 'Which of the following defenses could be used in a criminal trial?', 'multiple_choice'),
(50, 8, 'A misdemeanor is generally a more serious crime than a felony.', 'true_false'),
(51, 8, 'In criminal law, the prosecution must prove the defendant\'s guilt beyond a reasonable doubt.', 'true_false'),
(52, 8, 'Explain the concept of \"double jeopardy\" in criminal law.', 'short_answer');

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `is_correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `option_text`, `is_correct`) VALUES
(12, 5, 'A) Homicide  ', 0),
(13, 5, 'B) Suicide', 1),
(14, 5, 'C) Accidental death', 0),
(15, 5, ' D) Natural causes', 0),
(16, 6, 'True', 1),
(17, 7, 'Rebecca Zahau was found hanging naked from a balcony, with her hands and feet bound.', 1),
(18, 8, 'A) Adam Shacknai', 1),
(19, 8, 'B) Max Shacknai', 0),
(20, 8, 'C) Jonah Shacknai', 0),
(21, 8, 'D) Rebecca Zahau\'s brother', 0),
(22, 9, 'True', 1),
(23, 10, 'Max Shacknai, Jonah Shacknai’s young son, suffered a tragic fall while under Rebecca Zahau’s care, which many believed may have contributed to her death.', 1),
(28, 12, 'True', 1),
(29, 13, 'A) The evidence of foul play was insufficient.', 0),
(30, 13, 'B) There were conflicting reports from witnesses.', 0),
(31, 13, 'C) Rebecca’s hands were bound at the time of her death.', 1),
(32, 13, 'D) She had a history of suicidal behavior.', 0),
(33, 14, 'Rebecca Zahau’s family filed a wrongful death lawsuit, arguing that her death was a homicide and not a suicide as initially ruled.', 1),
(69, 18, 'a. She disappeared in a forest.', 0),
(70, 18, 'b. She was found in a water tank at a hotel.', 1),
(71, 18, 'c. She went missing during a hike.', 0),
(72, 18, 'd. She was involved in a famous robbery.', 0),
(73, 19, 'a. The Plaza Hotel', 0),
(74, 19, 'b. The Ritz-Carlton', 0),
(75, 19, 'c. The Cecil Hotel', 1),
(76, 19, 'd. The Grand Budapest Hotel', 0),
(77, 20, 'a. She pressed random buttons.', 0),
(78, 20, 'b. She appeared to be talking to someone unseen.', 0),
(79, 20, 'c. Both a and b.', 1),
(80, 20, 'd. Neither a nor b.', 0),
(81, 21, 'a. Writing crime novels.', 0),
(82, 21, 'b. Creating urban legends.', 0),
(83, 21, 'c. Being a private investigator.', 0),
(84, 21, 'd. There is no verified record of her.', 1),
(86, 23, 'True', 1),
(87, 24, 'True', 1),
(88, 25, '(Sample Answer: Elisa Lam\'s case brought widespread attention to the Cecil Hotel, highlighting its history of mysterious incidents and increasing its notoriety as a site for true crime enthusiasts.)', 1),
(89, 26, '(Sample Answer: Cases like Elisa Lam\'s are intriguing due to the unexplained nature of events, the involvement of video evidence, and the psychological and supernatural theories that arise.)', 1),
(110, 28, 'a. Costa Rica ', 0),
(111, 28, 'b. Panama', 1),
(112, 28, 'c. Colombia', 0),
(113, 28, 'd. Ecuador', 0),
(114, 29, 'a. On a vacation in the mountains.', 0),
(115, 29, 'b. Hiking in the jungle near Boquete.', 1),
(116, 29, 'c. Participating in a volunteer program.', 0),
(117, 30, 'A passport and clothes.', 0),
(118, 30, 'A backpack with personal items and a camera.', 1),
(119, 30, 'A phone with GPS coordinates.', 0),
(120, 31, 'False', 1),
(121, 32, 'True', 1),
(122, 33, '(Sample Answer: Several theories suggest they got lost while hiking, possibly due to poor weather or injury. There are also theories about foul play, an animal attack, or a mysterious encounter in the jungle.)', 1),
(123, 34, 'Pretending to be injured or disabled.', 1),
(124, 34, 'Offering rides to hitchhikers.', 0),
(125, 34, ' Seducing victims through fake job offers.', 0),
(126, 35, 'Washington', 0),
(127, 35, 'Florida', 0),
(128, 35, 'Utah', 0),
(129, 35, 'New York', 1),
(130, 36, '1972', 0),
(131, 36, '1974', 1),
(132, 36, '1976', 0),
(133, 37, '10-20', 0),
(134, 37, '20-30', 0),
(135, 37, '30-40', 1),
(136, 38, 'False', 1),
(137, 39, 'True', 1),
(138, 40, '2016', 0),
(139, 40, '2018', 1),
(140, 41, 'His workplace', 0),
(141, 41, ' His home in Colorado', 0),
(142, 41, '. A hotel room', 0),
(143, 42, 'They ran away.', 0),
(144, 42, 'They were kidnapped.', 1),
(145, 42, 'They went on a spontaneous trip', 0),
(146, 42, 'They were missing after a family argument.', 0),
(147, 43, 'False', 1),
(148, 44, 'Chris Watts confessed that he murdered his wife after a confrontation about their marriage and infidelity. He later killed his daughters to prevent them from being witnesses to the crime', 1),
(153, 46, 'a. Robbery', 0),
(154, 46, 'b. Embezzlement', 1),
(155, 46, 'c. Assault', 0),
(156, 46, 'd. Burglary', 0),
(157, 47, 'a. Misdemeanor', 0),
(158, 47, 'b. Felony', 1),
(159, 47, 'c. Infraction', 0),
(160, 48, 'a. To provide compensation to victims', 0),
(161, 48, ' b. To establish property rights', 0),
(162, 48, 'c. To punish and rehabilitate offenders', 1),
(163, 48, 'd. To ensure civil rights protection', 0),
(164, 49, 'a. Insanity defense', 0),
(165, 49, 'b. Lack of evidence', 0),
(166, 49, 'c. Alibi', 0),
(167, 49, 'd. All of the above', 1),
(168, 50, 'False', 1),
(169, 51, 'True', 1),
(170, 52, '(Sample Answer: Double jeopardy is a legal principle that prevents a person from being tried twice for the same offense once they have been acquitted or convicted.)', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `difficulty` enum('easy','medium','hard') NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `title`, `description`, `category_id`, `difficulty`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Rebecca Zahau Death Case Quiz', 'This quiz explores the mysterious and controversial death of Rebecca\r\n Zahau, whose case has been the subject of extensive media \r\ncoverage and public speculation.', 1, 'medium', '2024-12-24', '2025-01-10', 'active', '2024-12-24 14:45:50', '2024-12-24 14:49:18'),
(4, 'Elisa Lam and Kenica Jencans', 'Test your knowledge of unsolved mysteries and urban legends \r\nwith this quiz! Dive into the infamous Elisa Lam case, the enigmatic\r\n Kenica Jencans tales, and the chilling history of the Cecil Hotel.', 1, 'hard', '2025-01-01', '2025-09-01', 'active', '2025-01-01 10:09:05', '2025-01-02 11:14:56'),
(5, 'Mysterious Disappearance of Kris Kremers and Lisanne Froon', 'Test your knowledge of the mysterious disappearance of Kris Kremers and Lisanne Froon, two Dutch women who vanished while hiking in Panama in 2014.', 2, 'medium', '2025-01-01', '2025-01-09', 'active', '2025-01-01 10:52:29', '2025-01-02 11:16:05'),
(6, 'The Crimes of Ted Bundy: A Chilling Quiz', 'Test your knowledge of one of the most notorious serial \r\nkillers in history—Ted Bundy.', 3, 'medium', '2025-01-01', '2025-01-10', 'active', '2025-01-01 11:13:14', '2025-01-02 11:16:40'),
(7, 'The Chilling Case of Chris Watts: A Murderous Betrayal', 'Explore the haunting case of Chris Watts, who murdered \r\nhis wife Shanann and their two daughters in 2018.', 4, 'easy', '2025-01-01', '2025-01-18', 'active', '2025-01-01 11:23:08', '2025-01-02 11:17:09'),
(8, 'Understanding Crime Laws: A Special Quiz', 'Test your knowledge of crime laws with this quiz covering \r\nthe basics of criminal law.', 12, 'medium', '2025-01-01', '2025-01-24', 'active', '2025-01-01 11:36:02', '2025-01-02 11:17:33');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `attempt_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `user_id`, `quiz_id`, `score`, `attempt_date`) VALUES
(39, 2, 3, 3, '2024-12-24'),
(44, 2, 5, 6, '2025-01-02'),
(56, 2, 5, 2, '2025-01-03'),
(57, 2, 3, 9, '2025-01-09'),
(58, 21, 3, 7, '2025-01-09');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `content_type` enum('Breaking Crime News','trending_news','exclusive_content','top_shows') NOT NULL,
  `content_id` int(11) NOT NULL,
  `content_source` enum('articles','news','top_shows') NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `rate_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `content_type`, `content_id`, `content_source`, `user_id`, `rating`, `rate_date`) VALUES
(2, 'top_shows', 0, 'top_shows', 2, 4.0, '2025-01-09 02:04:40'),
(3, 'Breaking Crime News', 1, 'articles', 2, 4.0, '2024-11-23 04:01:55'),
(4, 'top_shows', 0, 'articles', 1, 4.0, '2024-12-09 14:17:08'),
(5, 'top_shows', 1, 'top_shows', 2, 4.0, '2024-12-09 14:18:18'),
(6, 'top_shows', 20, 'articles', 1, 4.0, '2024-12-11 15:19:08'),
(7, 'top_shows', 26, 'articles', 1, 4.0, '2024-12-11 17:01:22'),
(8, 'Breaking Crime News', 3, 'news', 2, 3.0, '2024-12-14 13:26:52'),
(9, 'Breaking Crime News', 6, 'articles', 2, 4.0, '2024-12-17 12:53:26'),
(10, 'top_shows', 13, 'articles', 2, 4.0, '2025-01-07 06:43:39'),
(11, 'top_shows', 19, 'articles', 2, 4.0, '2025-01-02 14:32:47'),
(12, 'Breaking Crime News', 2, 'articles', 2, 3.0, '2025-01-03 10:10:28'),
(13, 'top_shows', 26, 'articles', 2, 4.0, '2025-01-03 10:12:22'),
(14, 'top_shows', 12, 'articles', 2, 4.0, '2025-01-07 06:42:56'),
(15, 'top_shows', 21, 'articles', 2, 4.0, '2025-01-09 02:05:42'),
(16, 'Breaking Crime News', 20, 'articles', 21, 4.0, '2025-01-09 12:32:19');

-- --------------------------------------------------------

--
-- Table structure for table `saved_articles`
--

CREATE TABLE `saved_articles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `date_saved` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_articles`
--

INSERT INTO `saved_articles` (`id`, `user_id`, `article_id`, `date_saved`) VALUES
(1, 1, 1, '2024-10-31 19:30:22'),
(2, 2, 3, '2024-11-26 18:32:18'),
(3, 1, 20, '2024-12-11 17:19:13'),
(4, 2, 13, '2025-01-02 15:55:26'),
(5, 2, 19, '2025-01-02 16:32:52'),
(6, 2, 26, '2025-01-03 12:12:26'),
(7, 2, 21, '2025-01-09 04:05:47'),
(8, 21, 15, '2025-01-09 14:33:38');

-- --------------------------------------------------------

--
-- Table structure for table `saved_shows`
--

CREATE TABLE `saved_shows` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `show_id` int(11) NOT NULL,
  `date_saved` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_shows`
--

INSERT INTO `saved_shows` (`id`, `user_id`, `show_id`, `date_saved`) VALUES
(3, 2, 2, '2025-01-09 04:04:46');

-- --------------------------------------------------------

--
-- Table structure for table `session_activity`
--

CREATE TABLE `session_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `logout_time` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `device_info` varchar(255) NOT NULL,
  `activity_type` enum('web','mobile','api','other') NOT NULL DEFAULT 'web',
  `status` enum('active','inactive','expired') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `session_activity`
--

INSERT INTO `session_activity` (`id`, `user_id`, `session_id`, `login_time`, `logout_time`, `ip_address`, `device_info`, `activity_type`, `status`) VALUES
(13, 2, '7gb9a8ola21ns5d77mi6jehv6m', '2024-11-21 10:42:38', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(14, 2, '7gb9a8ola21ns5d77mi6jehv6m', '2024-11-21 15:42:56', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(15, 2, '96unvtaslvrjjekqc7gotmbsn4', '2024-11-22 17:32:06', '2024-11-22 18:04:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(16, 1, '96unvtaslvrjjekqc7gotmbsn4', '2024-11-22 18:41:13', '2024-11-22 17:47:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(17, 2, '96unvtaslvrjjekqc7gotmbsn4', '2024-11-22 18:48:43', '2024-11-22 18:04:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(18, 2, '96unvtaslvrjjekqc7gotmbsn4', '2024-11-22 19:05:03', '2024-11-22 18:37:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(19, 2, '96unvtaslvrjjekqc7gotmbsn4', '2024-11-22 19:37:58', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(21, 2, 'jn7vk3h11f5e2emnlov315s2ds', '2024-11-23 03:40:02', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(22, 1, '9g8pd913gec73497kb744lgkoa', '2024-11-25 20:08:03', '2024-11-25 19:08:43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(23, 2, '9g8pd913gec73497kb744lgkoa', '2024-11-25 20:08:52', '2024-11-25 19:09:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(24, 2, '9g8pd913gec73497kb744lgkoa', '2024-11-25 20:19:23', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(25, 2, '8gdloat362ea2qt88rha84p7pj', '2024-11-26 16:31:22', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(26, 2, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 09:46:38', '2024-11-29 13:37:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(27, 2, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 10:04:49', '2024-11-29 13:37:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(28, 2, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 11:03:53', '2024-11-29 13:37:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(29, 2, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 13:52:02', '2024-11-29 13:37:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(30, 2, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 14:32:38', '2024-11-29 13:37:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(31, 1, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 14:33:17', '2024-11-29 13:35:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(32, 2, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 14:35:27', '2024-11-29 13:37:36', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(34, 2, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 14:44:04', '2024-11-30 16:09:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(35, 2, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 15:15:23', '2024-11-30 16:09:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(36, 8, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 15:16:21', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(37, 2, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 17:53:27', '2024-11-30 16:09:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(38, 2, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-29 20:11:54', '2024-11-30 16:09:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(39, 1, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-30 17:15:34', '2024-11-30 16:20:58', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(40, 1, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-30 17:21:34', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(41, 20, 'oaotd8tjhp3oq1ig0h1buhi685', '2024-11-30 17:24:53', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(42, 2, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-03 10:01:13', '2024-12-03 09:15:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(43, 2, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-03 10:23:11', '2024-12-03 14:29:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(44, 2, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-03 11:56:31', '2024-12-03 14:29:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(45, 1, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-03 14:57:53', '2024-12-03 17:24:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(46, 2, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-03 15:16:23', '2024-12-03 14:29:52', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(47, 2, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-03 15:54:36', '2024-12-03 15:23:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(48, 2, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-03 16:31:02', '2024-12-03 15:40:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(49, 2, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-03 16:45:52', '2024-12-03 16:35:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(50, 1, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-03 18:05:24', '2024-12-03 17:24:38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(51, 1, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-04 17:25:29', '2024-12-04 16:39:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(52, 1, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-04 17:31:51', '2024-12-04 16:39:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(53, 2, 'c9c7gahc8e492jibjn3t2b145n', '2024-12-04 17:39:42', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(54, 1, 'r15kq5qal2n6cpq3b6jnpjj5n8', '2024-12-05 15:06:56', '2024-12-05 15:45:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(55, 2, 'r15kq5qal2n6cpq3b6jnpjj5n8', '2024-12-05 15:37:57', '2024-12-05 14:39:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(56, 1, 'r15kq5qal2n6cpq3b6jnpjj5n8', '2024-12-05 15:39:36', '2024-12-05 15:45:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(57, 2, 'r15kq5qal2n6cpq3b6jnpjj5n8', '2024-12-05 16:47:01', '2024-12-05 15:50:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(58, 1, 'r15kq5qal2n6cpq3b6jnpjj5n8', '2024-12-05 16:50:36', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(59, 1, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 12:18:25', '2024-12-06 12:23:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(60, 2, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 13:23:34', '2024-12-06 12:47:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(61, 1, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 13:47:45', '2024-12-06 13:27:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(62, 2, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 13:59:05', '2024-12-06 13:14:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(63, 1, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 14:14:35', '2024-12-06 13:27:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(64, 1, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 14:28:25', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(65, 2, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 14:58:54', '2024-12-06 14:00:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(66, 1, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 15:04:08', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(67, 2, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 15:20:02', '2024-12-06 15:12:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(68, 1, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 16:13:01', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(69, 1, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 17:59:33', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(70, 2, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 18:48:39', '2024-12-06 17:49:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(71, 1, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 18:49:35', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(72, 2, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 19:41:33', '2024-12-06 18:42:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(73, 1, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-06 19:43:09', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(74, 2, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-07 07:49:52', '2024-12-07 06:51:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(75, 1, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-07 07:51:18', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(76, 2, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-07 16:36:06', '2024-12-07 15:42:33', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(77, 1, 'fpr8cs53s5aam32jvpbre1rsip', '2024-12-07 16:42:51', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(78, 1, 'c8a08plj06bocp19vkj0bn7ubp', '2024-12-08 11:51:29', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(79, 2, 'c8a08plj06bocp19vkj0bn7ubp', '2024-12-08 12:15:36', '2024-12-08 11:26:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(80, 1, 'c8a08plj06bocp19vkj0bn7ubp', '2024-12-08 12:27:00', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(81, 1, 'c8a08plj06bocp19vkj0bn7ubp', '2024-12-08 13:09:33', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(82, 1, 'c8a08plj06bocp19vkj0bn7ubp', '2024-12-09 13:01:09', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(83, 2, 'jkqnlan8hutpe6kjo88bmvfkac', '2024-12-11 09:39:44', '2024-12-11 11:58:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(84, 1, 'jkqnlan8hutpe6kjo88bmvfkac', '2024-12-11 12:58:35', '2024-12-11 14:42:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(85, 1, 'jkqnlan8hutpe6kjo88bmvfkac', '2024-12-11 15:17:04', '2024-12-11 14:42:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(86, 1, 'jkqnlan8hutpe6kjo88bmvfkac', '2024-12-11 15:43:01', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(87, 2, 'jkqnlan8hutpe6kjo88bmvfkac', '2024-12-11 17:47:07', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(88, 2, 'ptqs37uhroe4j66lkup9riiem8', '2024-12-12 13:55:43', '2024-12-12 13:02:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(89, 1, 'ptqs37uhroe4j66lkup9riiem8', '2024-12-12 14:02:33', '2024-12-12 16:10:57', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(90, 2, 'ptqs37uhroe4j66lkup9riiem8', '2024-12-12 17:11:11', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(91, 1, 'ptqs37uhroe4j66lkup9riiem8', '2024-12-13 14:58:18', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(92, 2, '45kaf53c6m6361tdpa2e26nfjj', '2024-12-14 16:08:34', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(93, 2, 's01b2ovs52c029bd87vsginh9u', '2024-12-17 12:08:32', '2024-12-17 11:29:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(94, 2, 's01b2ovs52c029bd87vsginh9u', '2024-12-17 12:52:34', '2024-12-17 12:02:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(95, 1, 's01b2ovs52c029bd87vsginh9u', '2024-12-17 13:02:34', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(96, 1, 'issu213anfniib1bq8mmi1uud4', '2024-12-19 08:01:32', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(97, 1, 'amjq2pium8a138kgh6bipe0ltp', '2024-12-21 17:07:35', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(98, 2, 'amjq2pium8a138kgh6bipe0ltp', '2024-12-21 17:52:23', '2024-12-21 16:53:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(99, 1, 'amjq2pium8a138kgh6bipe0ltp', '2024-12-21 18:31:56', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(100, 1, 'btrd9nmprigmhshgan3c38dn8m', '2024-12-24 14:43:11', '2024-12-24 14:56:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(101, 2, 'btrd9nmprigmhshgan3c38dn8m', '2024-12-24 15:58:23', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(102, 1, 'btrd9nmprigmhshgan3c38dn8m', '2024-12-24 16:53:36', '2024-12-24 15:54:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(103, 1, 'btrd9nmprigmhshgan3c38dn8m', '2024-12-24 16:54:36', '2024-12-24 15:54:47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(104, 1, 'btrd9nmprigmhshgan3c38dn8m', '2024-12-24 16:56:18', '2024-12-24 16:00:04', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(105, 2, 'btrd9nmprigmhshgan3c38dn8m', '2024-12-24 17:00:20', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(106, 2, '4kplepd4l6pnrb8eoe43lackav', '2024-12-26 12:08:11', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(107, 2, '3cq6j605qnj1is9lrccb97rrbg', '2024-12-27 13:38:42', '2024-12-27 12:51:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(108, 1, '3cq6j605qnj1is9lrccb97rrbg', '2024-12-27 13:52:02', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(109, 1, 'v06nd58kdakjgb1olemgmh1p4j', '2024-12-29 14:19:56', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(110, 1, '61k5fm7g5th03rm04hj9gd63gi', '2025-01-01 10:05:55', '2025-01-02 09:33:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(111, 2, '61k5fm7g5th03rm04hj9gd63gi', '2025-01-01 10:45:54', '2025-01-01 09:51:21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(112, 1, '61k5fm7g5th03rm04hj9gd63gi', '2025-01-01 10:51:34', '2025-01-02 09:33:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(113, 1, '61k5fm7g5th03rm04hj9gd63gi', '2025-01-01 11:49:21', '2025-01-02 09:33:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(114, 1, '61k5fm7g5th03rm04hj9gd63gi', '2025-01-01 12:17:15', '2025-01-02 09:33:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(115, 1, '61k5fm7g5th03rm04hj9gd63gi', '2025-01-02 10:33:25', '2025-01-02 09:33:48', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(116, 1, '61k5fm7g5th03rm04hj9gd63gi', '2025-01-02 10:39:08', '2025-01-02 09:51:35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(117, 2, '61k5fm7g5th03rm04hj9gd63gi', '2025-01-02 10:51:47', '2025-01-02 09:51:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(118, 1, '61k5fm7g5th03rm04hj9gd63gi', '2025-01-02 10:52:05', '2025-01-02 09:56:19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(119, 1, '61k5fm7g5th03rm04hj9gd63gi', '2025-01-02 10:56:47', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(120, 1, 'ok116j3uqdfjmengdsm150v8vs', '2025-01-02 13:34:17', '2025-01-02 12:45:40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(121, 2, 'ok116j3uqdfjmengdsm150v8vs', '2025-01-02 13:46:10', '2025-01-02 13:22:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(122, 1, 'ok116j3uqdfjmengdsm150v8vs', '2025-01-02 14:22:30', '2025-01-02 13:23:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(123, 2, 'ok116j3uqdfjmengdsm150v8vs', '2025-01-02 14:23:50', '2025-01-02 13:25:20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(124, 1, 'ok116j3uqdfjmengdsm150v8vs', '2025-01-02 14:25:43', '2025-01-02 13:26:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(125, 2, 'ok116j3uqdfjmengdsm150v8vs', '2025-01-02 14:26:38', '2025-01-02 13:33:26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(126, 2, '7enfes7vijpcvbituhsdhm9bsj', '2025-01-03 10:09:51', '2025-01-03 09:18:15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(127, 1, '7enfes7vijpcvbituhsdhm9bsj', '2025-01-03 10:18:32', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(128, 1, 't0pgvq0h7cl5dv2es1k98g6rs2', '2025-01-03 18:15:39', '2025-01-03 17:16:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(129, 1, 'cs1pq06u7m5stiguepffr681nf', '2025-01-03 18:19:10', '2025-01-03 17:19:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(130, 2, 'cs1pq06u7m5stiguepffr681nf', '2025-01-03 18:19:34', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(131, 2, 'la9tigjb3qvh1pvfa0604jvuu1', '2025-01-04 12:44:23', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(132, 2, 'la9tigjb3qvh1pvfa0604jvuu1', '2025-01-04 12:48:58', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(133, 2, 'rudkedov9d9befdm5c40gfrkav', '2025-01-04 14:56:10', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(134, 2, '7ge4gkjd1rps2ag18639a7ca3e', '2025-01-04 14:59:31', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(135, 1, '5tkh18nqmhdu94nsi4iijrfqic', '2025-01-07 06:40:08', '2025-01-07 05:40:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(136, 2, '5tkh18nqmhdu94nsi4iijrfqic', '2025-01-07 06:40:54', '2025-01-07 05:51:09', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(137, 1, '5tkh18nqmhdu94nsi4iijrfqic', '2025-01-07 06:51:16', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active'),
(138, 1, 'j5mmccaq9f4ff476j036h20dgi', '2025-01-08 14:56:45', '2025-01-08 13:57:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(139, 2, 'j5mmccaq9f4ff476j036h20dgi', '2025-01-08 14:57:45', '2025-01-08 13:59:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(140, 2, 'jjvt4731j953vna3rrv8rtr0ql', '2025-01-09 01:54:45', '2025-01-09 00:55:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(141, 1, 'jjvt4731j953vna3rrv8rtr0ql', '2025-01-09 01:55:27', '2025-01-09 01:01:18', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(142, 2, 'jjvt4731j953vna3rrv8rtr0ql', '2025-01-09 02:04:05', '2025-01-09 01:09:32', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(143, 2, 'g8aj68ugan5ucorepu6r9aldv6', '2025-01-09 10:55:17', '2025-01-09 10:07:56', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(144, 21, 'g8aj68ugan5ucorepu6r9aldv6', '2025-01-09 12:31:15', '2025-01-09 11:39:55', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(145, 1, 'g8aj68ugan5ucorepu6r9aldv6', '2025-01-09 12:40:15', '2025-01-09 11:42:17', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(146, 1, 'g8aj68ugan5ucorepu6r9aldv6', '2025-01-09 12:42:59', '2025-01-09 11:43:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'inactive'),
(147, 1, 'g8aj68ugan5ucorepu6r9aldv6', '2025-01-09 12:43:57', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'web', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `article_id` int(11) DEFAULT NULL,
  `tag_name` varchar(255) DEFAULT NULL,
  `shows_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `article_id`, `tag_name`, `shows_id`) VALUES
(3, 1, '#rebecca #max', NULL),
(4, 2, '#max', NULL),
(6, 3, '#elisa #lam', NULL),
(7, 4, '#kennica ', NULL),
(8, 10, '#liz #kris', NULL),
(9, 11, '#faye ', NULL),
(10, 12, '#tedy #bendy ', NULL),
(11, 13, '#gray ', NULL),
(12, 14, '#watts #kiledhisfamily', NULL),
(13, 15, '#gonnan #killedbymother ', NULL),
(14, 16, '#natli #disappear', NULL),
(15, 17, '#eliz #smart', NULL),
(16, 18, '#eliz holomes ', NULL),
(17, 19, '#darek', NULL),
(19, 20, '#criminal #law ', NULL),
(20, 21, '#legal_process #criminal', NULL),
(21, 22, '#crime #law #punishment', NULL),
(22, 25, '#constitutional #protect_killer_laws', NULL),
(23, 20, '#crimials_law', NULL),
(24, 21, '#arrest', NULL),
(25, 22, '#laws', NULL),
(26, 25, '#constitutional law', NULL),
(27, 25, '#gulity_rights', NULL),
(28, 27, '#human_rights', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `bio` text NOT NULL,
  `profile_image_url` varchar(255) DEFAULT NULL,
  `social_links` text DEFAULT NULL,
  `joined_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `position`, `bio`, `profile_image_url`, `social_links`, `joined_date`) VALUES
(2, 'Admin', 'Admin', 'Admin oversees every aspect of Breathe News, ensuring seamless operations, high-quality content, and a cohesive team effort. With a strong vision and exceptional leadership skills, they steer the platform toward continuous growth and excellence.', 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBwgHBgkIBwgKCgkLDRYPDQwMDRsUFRAWIB0iIiAdHx8kKDQsJCYxJx8fLT0tMTU3Ojo6Iys/RD84QzQ5OjcBCgoKDQwNGg8PGjclHyU3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3Nzc3N//AABEIAJQAmwMBIgACEQEDEQH/', '...', '2024-12-03'),
(3, 'Miriana', 'Admin', 'Miriana is a cornerstone of our team, bringing creativity and precision to every task. Whether it’s curating stories, managing editorial schedules, or supporting team initiatives, her dedication and attention to detail shine through.', '...', '.../', '2024-12-03');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `approved` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `user_id`, `name`, `content`, `approved`, `created_at`) VALUES
(1, 2, 'ali', '\"The hiring offers section is an innovative idea, and it’s great for people interested in crime-related careers.\"', 1, '2024-11-25 20:20:27'),
(3, 20, 'karim', '\"The variety of crime-related topics covered is impressive. I always find something new and interesting to read.\"', 1, '2025-01-09 02:02:03'),
(4, 8, 'amir', '\"I love the sidebar menu—it makes navigation very easy. The overall layout is clean and intuitive.\"\r\n', 1, '2025-01-09 02:03:07'),
(5, 21, '', 'htf', 1, '2025-01-09 12:39:50');

-- --------------------------------------------------------

--
-- Table structure for table `top_shows`
--

CREATE TABLE `top_shows` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `publish_date` date DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `why_is_popular` text NOT NULL,
  `why_viewers_love` text NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `top_shows`
--

INSERT INTO `top_shows` (`id`, `title`, `description`, `thumbnail`, `publish_date`, `category_id`, `status`, `video`, `why_is_popular`, `why_viewers_love`, `category_name`) VALUES
(2, 'Unsolved Mysteries (Netflix)', 'is a revival of the iconic true-crime documentary series \r\nthat originally aired in the late 1980s. The Netflix\r\n adaptation combines gripping storytelling with\r\n reenactments, interviews, and archival footage to\r\n explore perplexing cases that remain unresolved to this day.', 'unsolved mysteries.jfif', '2024-12-07', 1, 'active', 'Unsolved Mysteries   Official Trailer   Netflix.mp4', 'The series is praised for its cinematic quality and emotionally\r\n compelling storytelling. With a mix of true crime and \r\nsupernatural intrigue, it captivates both mystery enthusiasts \r\nand fans of investigative journalism.', 'Intriguing real-life mysteries.\r\nHigh-quality reenactments and interviews.\r\nAn open-ended format that invites public engagement.\r\n', 'Cold Cases'),
(4, 'The Disappearance of Madeleine McCann (Netflix)', 'is a documentary series that explores the 2007 disappearance \r\nof three-year-old Madeleine McCann, who vanished from a \r\nresort in Praia da Luz, Portugal, while on vacation with her family.\r\n The series takes a deep dive into the case, examining the \r\ninvestigation, the media frenzy, the theories surrounding the \r\ndisappearance, and the impact it had on the McCann family.', 'download (2).jfif', '2024-12-07', 1, 'inactive', 'The Disappearance of Madeleine McCann   Official Trailer [HD]   Netflix.mp4', 'The series is popular due to its detailed, investigative approach and \r\nthe fact that the disappearance of Madeleine McCann remains one \r\nof the most high-profile missing persons cases in recent history. \r\nThe emotional aspect of the case, paired with the ongoing mystery,\r\n draws in viewers who are captivated by the unresolved nature of the case.', 'Unsolved Mystery: Viewers are drawn to the ongoing mystery and\r\nthe wide range of theories about Madeleine\'s disappearance.\r\nIn-depth Exploration: The documentary takes a comprehensive \r\napproach, examining the case from multiple angles and offering\r\n insight into the impact of the media on the investigation', 'Cold Cases'),
(5, 'The Confession Tapes (Netflix)', 'The Confession Tapes is a true crime series that investigates\r\n cases where individuals confess to crimes they may not have\r\n committed. Each episode examines the confession tapes, \r\npolice interrogations, and legal proceedings, questioning the \r\nfairness of these confessions and highlighting potential wrongful\r\n convictions.', 'download (3).jfif', '2024-12-07', 1, 'active', 'The Confession Tapes trailer.mp4', 'True Crime Fascination: The series appeals to fans of true crime with its real-life cases and suspenseful storytelling.\r\nFocus on Justice: It highlights wrongful convictions, sparking discussions on justice and the flaws in the legal system.', 'Educational: The series offers an educational look at the justice system, particularly the risks associated with forced confessions, which makes it both informative and entertaining.\r\nDiscussion-Worthy: It sparks conversation among viewers, as they discuss the ethical issues surrounding coerced confessions, wrongful convictions, and the failures of the legal system.', 'Cold Cases'),
(6, 'The Keepers (Netflix)', 'is a seven-episode documentary series that explores \r\nthe unsolved murder of Sister Cathy Cesnik, a beloved\r\n nun and teacher at Archbishop Keough High School \r\nin Baltimore. The series delves into the secrets of the\r\n school, uncovering allegations of sexual abuse, \r\ncover-ups, and a web of conspiracies surrounding\r\n the case.', 'the keepers.jfif', '2024-12-08', 2, 'inactive', 'The Keepers   Official Trailer [HD]   Netflix.mp4', 'Real-Life Investigation: The investigative nature of the \r\ndocumentary invites viewers to play detective, piecing\r\n together evidence with the victims\' testimonies.\r\nCritical Acclaim: High praise from critics and viewers alike\r\n adds to its popularity, with many hailing it as one of the\r\n most impactful crime documentaries.', 'Compelling True Crime: The series is gripping due to its emotional depth\r\n and chilling narrative. Viewers are drawn into the mystery surrounding \r\nSister Cathy\'s murder and the impact on the victims.\r\nUnraveling a Dark Story: It explores corruption and secrecy \r\nwithin an institution, making it not just a crime story, but \r\none that ties into larger themes of societal issues like sexual\r\n abuse and institutional cover-ups.', 'Unsolved Mysteries'),
(7, 'The Staircase (HBO)', 'follows  the real-life trial of Michael Peterson, who was\r\n accused of murdering his wife, Kathleen Peterson, after\r\n she was found dead at the bottom of a staircase in their\r\n home. The documentary offers unprecedented access to\r\n Peterson’s defense team as they navigate the complex\r\n legal case and examine the inconsistencies in the investigation.', 'the staircase.jfif', '2024-12-08', 2, 'active', 'The Staircase   Official Trailer   Sky Atlantic.mp4', 'Access to the Legal Process: The documentary gives a rare,\r\n behind-the-scenes look at a real-life legal defense, including\r\n interviews with the accused, his family, and the defense team.\r\nMoral Ambiguity: The series doesn’t provide easy answers,\r\n which keeps viewers engaged as they try to make sense\r\n of the evidence and motivations behind the case.', 'Controversial and Complex: The ambiguity of the case —\r\n whether it was a tragic accident or a calculated murder —\r\nfuels ongoing debates among viewers, making it a \r\nwatercooler topic.', 'Unsolved Mysteries'),
(8, 'Missing 411 (Amazon Prime)', 'Missing 411 is a documentary series based on the books by\r\n David Paulides, exploring mysterious disappearances in national\r\n parks across North America. The documentary connects \r\nseemingly unrelated cases of individuals vanishing without\r\n a trace in areas with strange circumstances, presenting \r\ntheories ranging from the plausible to the bizarre.', 'the mising.jfif', '2024-12-08', 2, 'active', 'Missing 411 The Hunted- Official Trailer (2019)-Scroll Down for Purchase Points.mp4', 'Mysterious and Intriguing: The unexplained disappearances in \r\nnational parks captivate viewers who are intrigued by unsolved\r\n mysteries and paranormal theories.\r\nA Different Perspective on Disappearances: Unlike typical true crime\r\n stories, this series dives into the unknown, offering possible \r\nexplanations for cases that defy logic.', 'Unique Concept: The focus on national park disappearances is a\r\n niche yet fascinating subject that hasn’t been heavily covered\r\n in mainstream media.', 'Unsolved Mysteries'),
(9, 'Mindhunter (Netflix)', 'psychological crime thriller series based on the true-crime \r\nbook Mindhunter: Inside the FBI\'s Elite Serial Crime Unit by\r\n John E. Douglas and Mark Olshaker. The show is set in the \r\nlate 1970s and follows two FBI agents, Holden Ford and Bill\r\n Tench, who interview imprisoned serial killers to understand \r\nhow they think in order to solve ongoing cases. They work \r\nalongside Wendy Carr, a psychologist, to develop criminal\r\n profiling techniques.', 'mindhunter.jfif', '2024-12-09', 3, 'active', 'Netflix - Mindhunter Season 1 Trailer.mp4', 'Deep Psychological Insights: The show delves into the minds of\r\n serial killers, offering viewers an intense psychological \r\nexploration of criminal behavior.', 'Fascinating Characters: The show’s focus on the development of\r\n criminal profiling and interactions with notorious criminals like\r\n Ed Kemper and Richard Speck adds a unique angle to the crime\r\n drama genre.', ''),
(10, 'The Ted Bundy Tapes (Netflix)', 'This documentary series examines the life and crimes of Ted Bundy, \r\none of America\'s most notorious serial killers. It includes real footage\r\n and audio recordings of Bundy during his time on death row, \r\noffering insight into his personality and the terrifying nature of\r\n his crimes. The series features interviews with law enforcement,\r\n survivors, and psychologists who analyze Bundy’s actions.', 'tedy.jfif', '2024-12-09', 3, 'active', 'Who Was Ted Bundy.mp4', 'Chilling Access to Bundy’s Mind: The archival footage and\r\n audio recordings offer a chilling glimpse into Bundy’s psyche, \r\nmaking it a must-watch for true crime enthusiasts.', 'Enduring Fascination with Ted Bundy: Bundy is one of the most\r\n infamous serial killers, and his case remains a subject of \r\nwidespread interest. The series taps into this cultural obsession.', ''),
(11, 'The Zodiac Killer (Discovery+)', 'This documentary investigates the infamous Zodiac Killer, \r\na serial killer who terrorized the San Francisco Bay Area in \r\nthe late 1960s and early 1970s. The killer sent cryptic letters \r\nto newspapers and taunted the police, leaving behind a trail\r\n of unsolved cases. The show explores the evidence, theories\r\n and suspects in an attempt to finally identify the Zodiac Killer.', 'zodi.jfif', '2024-12-09', 3, 'active', 'This is the Zodiac Speaking   Official Trailer   Netflix.mp4', 'Cultural Fascination with Cold Cases: The Zodiac Killer is one of \r\nthe most famous unsolved mysteries in American history, which\r\n keeps the case relevant and intriguing.', 'Enduring Mystery: The Zodiac Killer case remains unsolved, \r\nand viewers are drawn to the ongoing quest to crack the case \r\nafter decades of uncertainty.', ''),
(12, 'The Innocent Man (Netflix)', 'The Innocent Man is a true-crime documentary series based\r\n on John Grisham’s non-fiction book about two men who \r\nwere wrongfully convicted of murder in Ada, Oklahoma. \r\nThe series examines the legal battle to prove their innocence\r\n and the flaws in the criminal justice system that led to \r\ntheir convictions.', 'the innon man.jfif', '2024-12-09', 7, 'active', 'The Innocent Man   Official Trailer [HD]   Netflix.mp4', 'Timely and Relevant: Issues of wrongful conviction and \r\npolice misconduct are more relevant than ever, making \r\nthe series resonate with modern viewers.', 'Insight into the Justice System: The series sheds light on\r\n how the justice system can fail individuals, particularly \r\nthose who are wrongfully accused.', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `user_id` int(11) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`user_id`, `profile`, `first_name`, `last_name`, `bio`) VALUES
(1, 'signature.png', 'admin', '1', 'jjdj'),
(2, 'Screenshot (325).png', 'ali', 'khalil1', 'gffhgh'),
(18, 'gettyimages-1481606316-612x612.jpg', 'moe', 'khalil', '            americN WRITER'),
(20, 'er_diagram.png', 'karim', 'koko', '            abc'),
(21, '', 'ziad', 'kassam ', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_responses`
--

CREATE TABLE `user_responses` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `response_text` text DEFAULT NULL,
  `response_option_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_responses`
--

INSERT INTO `user_responses` (`id`, `attempt_id`, `question_id`, `response_text`, `response_option_id`) VALUES
(193, 0, 5, NULL, 13),
(194, 0, 6, NULL, 0),
(195, 0, 7, NULL, 0),
(196, 0, 8, NULL, 18),
(197, 0, 9, NULL, 0),
(198, 0, 10, NULL, 0),
(199, 0, 12, NULL, 0),
(200, 0, 13, NULL, 29),
(201, 0, 14, NULL, 0),
(202, 0, 5, NULL, 13),
(203, 0, 6, NULL, 0),
(204, 0, 7, NULL, 0),
(205, 0, 8, NULL, 18),
(206, 0, 9, NULL, 0),
(207, 0, 10, NULL, 0),
(208, 0, 12, NULL, 0),
(209, 0, 13, NULL, 29),
(210, 0, 14, NULL, 0),
(211, 0, 7, NULL, 0),
(212, 0, 10, NULL, 0),
(213, 0, 14, NULL, 0),
(214, 0, 5, NULL, 13),
(215, 0, 6, NULL, 0),
(216, 0, 7, NULL, 0),
(217, 0, 8, NULL, 18),
(218, 0, 9, NULL, 0),
(219, 0, 10, NULL, 0),
(220, 0, 12, NULL, 0),
(221, 0, 13, NULL, 29),
(222, 0, 14, NULL, 0),
(223, 0, 5, NULL, 13),
(224, 0, 6, NULL, 0),
(225, 0, 7, NULL, 0),
(226, 0, 8, NULL, 18),
(227, 0, 9, NULL, 0),
(228, 0, 10, NULL, 0),
(229, 0, 12, NULL, 0),
(230, 0, 13, NULL, 29),
(231, 0, 14, NULL, 0),
(232, 33, 5, NULL, 13),
(233, 33, 6, NULL, 0),
(234, 33, 7, NULL, 0),
(235, 33, 8, NULL, 18),
(236, 33, 9, NULL, 0),
(237, 33, 10, NULL, 0),
(238, 33, 12, NULL, 0),
(239, 33, 13, NULL, 29),
(240, 33, 14, NULL, 0),
(241, 34, 5, NULL, 13),
(242, 34, 6, NULL, 0),
(243, 34, 7, NULL, 0),
(244, 34, 8, NULL, 18),
(245, 34, 9, NULL, 0),
(246, 34, 10, NULL, 0),
(247, 34, 12, NULL, 0),
(248, 34, 13, NULL, 29),
(249, 34, 14, NULL, 0),
(250, 35, 5, NULL, 13),
(251, 35, 6, NULL, 0),
(252, 35, 7, NULL, 0),
(253, 35, 8, NULL, 18),
(254, 35, 9, NULL, 0),
(255, 35, 10, NULL, 0),
(256, 35, 12, NULL, 0),
(257, 35, 13, NULL, 29),
(258, 35, 14, NULL, 0),
(259, 36, 5, NULL, 13),
(260, 36, 6, NULL, 0),
(261, 36, 7, NULL, 0),
(262, 36, 8, NULL, 18),
(263, 36, 9, NULL, 0),
(264, 36, 10, NULL, 0),
(265, 36, 12, NULL, 0),
(266, 36, 13, NULL, 31),
(267, 36, 14, NULL, 0),
(268, 37, 7, NULL, 0),
(269, 37, 10, NULL, 0),
(270, 37, 14, NULL, 0),
(271, 38, 5, NULL, 13),
(272, 38, 6, NULL, 0),
(273, 38, 7, NULL, 0),
(274, 38, 8, NULL, 19),
(275, 38, 9, NULL, 0),
(276, 38, 10, NULL, 0),
(277, 38, 12, NULL, 0),
(278, 38, 13, NULL, 30),
(279, 38, 14, NULL, 0),
(280, 39, 5, NULL, 13),
(281, 39, 6, NULL, 0),
(282, 39, 7, NULL, 0),
(283, 39, 8, NULL, 19),
(284, 39, 9, NULL, 0),
(285, 39, 10, NULL, 0),
(286, 39, 12, NULL, 0),
(287, 39, 13, NULL, 30),
(288, 39, 14, NULL, 0),
(289, 40, 5, NULL, 13),
(290, 40, 6, NULL, 0),
(291, 40, 7, NULL, 0),
(292, 40, 8, NULL, 19),
(293, 40, 9, NULL, 0),
(294, 40, 10, NULL, 0),
(295, 40, 12, NULL, 0),
(296, 40, 13, NULL, 30),
(297, 40, 14, NULL, 0),
(298, 41, 5, NULL, 13),
(299, 41, 6, NULL, 0),
(300, 41, 7, NULL, 0),
(301, 41, 8, NULL, 19),
(302, 41, 9, NULL, 0),
(303, 41, 10, NULL, 0),
(304, 41, 12, NULL, 0),
(305, 41, 13, NULL, 30),
(306, 41, 14, NULL, 0),
(307, 42, 5, NULL, 13),
(308, 42, 6, NULL, 0),
(309, 42, 7, NULL, 0),
(310, 42, 8, NULL, 19),
(311, 42, 9, NULL, 0),
(312, 42, 10, NULL, 0),
(313, 42, 12, NULL, 0),
(314, 42, 13, NULL, 30),
(315, 42, 14, NULL, 0),
(316, 43, 5, NULL, 13),
(317, 43, 6, NULL, 0),
(318, 43, 7, NULL, 0),
(319, 43, 8, NULL, 19),
(320, 43, 9, NULL, 0),
(321, 43, 10, NULL, 0),
(322, 43, 12, NULL, 0),
(323, 43, 13, NULL, 30),
(324, 43, 14, NULL, 0),
(325, 44, 28, NULL, 111),
(326, 44, 29, NULL, 115),
(327, 44, 30, NULL, 118),
(328, 44, 31, NULL, 0),
(329, 44, 32, NULL, 0),
(330, 44, 33, NULL, 0),
(331, 45, 28, NULL, 111),
(332, 45, 29, NULL, 115),
(333, 45, 30, NULL, 118),
(334, 45, 31, NULL, 0),
(335, 45, 32, NULL, 0),
(336, 45, 33, NULL, 0),
(337, 46, 28, NULL, 111),
(338, 46, 29, NULL, 115),
(339, 46, 30, NULL, 118),
(340, 46, 31, NULL, 0),
(341, 46, 32, NULL, 0),
(342, 46, 33, NULL, 0),
(343, 47, 28, NULL, 111),
(344, 47, 29, NULL, 115),
(345, 47, 30, NULL, 118),
(346, 47, 31, NULL, 0),
(347, 47, 32, NULL, 0),
(348, 47, 33, NULL, 0),
(349, 48, 28, NULL, 111),
(350, 48, 29, NULL, 115),
(351, 48, 30, NULL, 118),
(352, 48, 31, NULL, 0),
(353, 48, 32, NULL, 0),
(354, 48, 33, NULL, 0),
(355, 49, 28, NULL, 111),
(356, 49, 29, NULL, 115),
(357, 49, 30, NULL, 118),
(358, 49, 31, NULL, 0),
(359, 49, 32, NULL, 0),
(360, 49, 33, NULL, 0),
(361, 50, 28, NULL, 111),
(362, 50, 29, NULL, 115),
(363, 50, 30, NULL, 118),
(364, 50, 31, NULL, 0),
(365, 50, 32, NULL, 0),
(366, 50, 33, NULL, 0),
(367, 51, 28, NULL, 111),
(368, 51, 29, NULL, 115),
(369, 51, 30, NULL, 118),
(370, 51, 31, NULL, 0),
(371, 51, 32, NULL, 0),
(372, 51, 33, NULL, 0),
(373, 52, 28, NULL, 111),
(374, 52, 29, NULL, 115),
(375, 52, 30, NULL, 118),
(376, 52, 31, NULL, 0),
(377, 52, 32, NULL, 0),
(378, 52, 33, NULL, 0),
(379, 53, 28, NULL, 111),
(380, 53, 29, NULL, 115),
(381, 53, 30, NULL, 118),
(382, 53, 31, NULL, 0),
(383, 53, 32, NULL, 0),
(384, 53, 33, NULL, 0),
(385, 54, 28, NULL, 111),
(386, 54, 29, NULL, 115),
(387, 54, 30, NULL, 118),
(388, 54, 31, NULL, 0),
(389, 54, 32, NULL, 0),
(390, 54, 33, NULL, 0),
(391, 55, 28, NULL, 111),
(392, 55, 29, NULL, 115),
(393, 55, 30, NULL, 118),
(394, 55, 31, NULL, 0),
(395, 55, 32, NULL, 0),
(396, 55, 33, NULL, 0),
(397, 56, 28, NULL, 111),
(398, 56, 29, NULL, 115),
(399, 56, 30, NULL, 117),
(400, 56, 31, NULL, 0),
(401, 56, 32, NULL, 0),
(402, 56, 33, NULL, 0),
(403, 57, 5, NULL, 13),
(404, 57, 6, NULL, 0),
(405, 57, 7, NULL, 0),
(406, 57, 8, NULL, 18),
(407, 57, 9, NULL, 0),
(408, 57, 10, NULL, 0),
(409, 57, 12, NULL, 0),
(410, 57, 13, NULL, 31),
(411, 57, 14, NULL, 0),
(412, 58, 5, NULL, 13),
(413, 58, 6, NULL, 0),
(414, 58, 7, NULL, 0),
(415, 58, 8, NULL, 18),
(416, 58, 9, NULL, 0),
(417, 58, 10, NULL, 0),
(418, 58, 12, NULL, 0),
(419, 58, 13, NULL, 29),
(420, 58, 14, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `website_info`
--

CREATE TABLE `website_info` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `website_info`
--

INSERT INTO `website_info` (`id`, `title`, `content`, `created_at`, `updated_at`) VALUES
(2, 'Welcome to Breathe News', 'Welcome to Breathe News, your trusted source for everything related to crime and justice. Our platform is dedicated to delivering accurate, in-depth, and timely information about crimes and their impact on society. Whether you\'re looking for detailed news articles, gripping crime shows, engaging videos, or insightful commentary, we’ve got you covered.\r\n\r\nAt Breathe News, we believe in fostering awareness and understanding. Beyond reporting, we offer interactive features like quizzes and polls to deepen user engagement and provide unique job opportunities in the crime and law enforcement sectors. Our commitment is to keep you informed, empowered, and connected to the stories that matter most.\r\n\r\nJoin us in exploring the complexities of crime and justice—because awareness is the first step towards change.', '2024-12-03 15:00:14', '2024-12-08 11:57:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_offers`
--
ALTER TABLE `job_offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_offer_stats`
--
ALTER TABLE `job_offer_stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestones`
--
ALTER TABLE `milestones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_tags`
--
ALTER TABLE `news_tags`
  ADD PRIMARY KEY (`news_id`),
  ADD KEY `fk_article_id` (`article_id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_options`
--
ALTER TABLE `poll_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `content_id` (`content_id`,`content_source`);

--
-- Indexes for table `saved_articles`
--
ALTER TABLE `saved_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_shows`
--
ALTER TABLE `saved_shows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `show_id` (`show_id`);

--
-- Indexes for table `session_activity`
--
ALTER TABLE `session_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `fk_tags_shows` (`shows_id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_testimonials_user` (`user_id`);

--
-- Indexes for table `top_shows`
--
ALTER TABLE `top_shows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_responses`
--
ALTER TABLE `user_responses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `website_info`
--
ALTER TABLE `website_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job_offers`
--
ALTER TABLE `job_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `job_offer_stats`
--
ALTER TABLE `job_offer_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `milestones`
--
ALTER TABLE `milestones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `news_tags`
--
ALTER TABLE `news_tags`
  MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `polls`
--
ALTER TABLE `polls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `poll_options`
--
ALTER TABLE `poll_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `poll_votes`
--
ALTER TABLE `poll_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `saved_articles`
--
ALTER TABLE `saved_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `saved_shows`
--
ALTER TABLE `saved_shows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `session_activity`
--
ALTER TABLE `session_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `top_shows`
--
ALTER TABLE `top_shows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_responses`
--
ALTER TABLE `user_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=421;

--
-- AUTO_INCREMENT for table `website_info`
--
ALTER TABLE `website_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `authors`
--
ALTER TABLE `authors`
  ADD CONSTRAINT `authors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `admin_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `news_tags`
--
ALTER TABLE `news_tags`
  ADD CONSTRAINT `fk_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_login` (`id`);

--
-- Constraints for table `saved_shows`
--
ALTER TABLE `saved_shows`
  ADD CONSTRAINT `saved_shows_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_login` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_shows_ibfk_2` FOREIGN KEY (`show_id`) REFERENCES `top_shows` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `session_activity`
--
ALTER TABLE `session_activity`
  ADD CONSTRAINT `session_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin_login` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `fk_tags_shows` FOREIGN KEY (`shows_id`) REFERENCES `top_shows` (`id`),
  ADD CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `fk_testimonials_user` FOREIGN KEY (`user_id`) REFERENCES `admin_login` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

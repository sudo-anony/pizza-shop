<div class=" py-4 px-0 m-0 font-light text-left  border-b-0 border-t border-gray-200 border-solid border-x-0 text-neutral-700 bg-white"
    style="border-width: 0px; z-index: 99; box-shadow: rgba(0, 0, 0, 0.05) 0px 0px 0px 1px;">
    <div  class="flex h-full items-center justify-center"
        style="">
        <div class="p-0 m-0 text-left  border-0">
            <nav lass="block p-0 m-0"
                style="overflow: auto hidden;">
                <!-- Display Social media links -->

                <?php
                    //Ge the social media links from company
                    $facebook = $restorant->getConfig('facebook','');
                    $instagram = $restorant->getConfig('instagram','');
                    $twitter = $restorant->getConfig('twitter','');
                    $youtube = $restorant->getConfig('youtube','');
                    $website = $restorant->getConfig('website','');
                    $phone = $restorant->phone;
                ?>

                <div class="flex flex-row space-x-10">
                   <!-- Facebook Button -->
                   @if (strlen($facebook)>2)
                   <div class='item'>
                       <a href='{{ $facebook }}'>
                           <svg fill="#1C2033" width="24" height="24" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                               <path d="M36.2008 63.8002H28.4008C26.4008 63.8002 24.8008 62.1002 24.8008 60.0002V36.2002H18.4008C16.4008 36.2002 14.8008 34.5002 14.8008 32.4002V25.5002C14.8008 23.4002 16.4008 21.7002 18.4008 21.7002H24.6008V15.4002C24.6008 6.30019 30.0008 0.200195 38.0008 0.200195H44.0008C46.0008 0.200195 47.6008 1.9002 47.6008 4.0002V12.1002C47.6008 14.2002 46.0008 15.9002 44.0008 15.9002H39.9008C39.8008 15.9002 39.8008 15.9002 39.7008 15.9002C39.7008 16.0002 39.7008 16.1002 39.7008 16.2002V21.6002H45.4008C46.6008 21.7002 47.6008 22.2002 48.3008 23.0002C49.0008 23.9002 49.3008 25.1002 49.1008 26.2002L47.9008 33.0002C47.7008 34.8002 46.2008 36.1002 44.3008 36.1002H39.7008V60.0002C39.7008 62.0002 38.1008 63.8002 36.2008 63.8002ZM26.5008 32.7002C27.5008 32.7002 28.3008 33.5002 28.3008 34.5002V60.0002C28.3008 60.2002 28.4008 60.3002 28.4008 60.3002H36.2008C36.2008 60.3002 36.3008 60.2002 36.3008 60.0002V34.3002C36.3008 33.3002 37.1008 32.5002 38.1008 32.5002H44.4008C44.4008 32.5002 44.5008 32.5002 44.5008 32.4002V32.3002L45.7008 25.6002C45.7008 25.4002 45.7008 25.3002 45.6008 25.2002C45.6008 25.2002 45.5008 25.1002 45.4008 25.1002H38.0008C37.0008 25.1002 36.2008 24.3002 36.2008 23.3002V16.2002C36.2008 14.4002 36.5008 12.4002 39.9008 12.4002H44.0008C44.0008 12.4002 44.1008 12.3002 44.1008 12.1002V4.1002C44.1008 3.9002 44.0008 3.8002 44.0008 3.8002H38.1008C32.1008 3.8002 28.2008 8.4002 28.2008 15.5002V23.6002C28.2008 24.6002 27.4008 25.4002 26.4008 25.4002H18.4008C18.4008 25.4002 18.3008 25.5002 18.3008 25.7002V32.6002C18.3008 32.8002 18.4008 32.9002 18.4008 32.9002L26.5008 32.7002Z"/>
                               </svg>
                               
                       </a>
                   </div>
               @endif

               <!-- Instagram Button -->
               @if (strlen($instagram)>2)
                   <div class='item'>
                       <a href='{{ $instagram }}'>
                           <svg fill="#1C2033" width="24" height="24" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                               <path d="M32.0016 17.5996C24.1016 17.5996 17.6016 23.9996 17.6016 31.9996C17.6016 39.8996 24.0016 46.3996 32.0016 46.3996C39.9016 46.3996 46.3016 39.9996 46.3016 31.9996C46.3016 24.0996 39.9016 17.5996 32.0016 17.5996ZM32.0016 41.8996C26.6016 41.8996 22.1016 37.4996 22.1016 31.9996C22.1016 26.5996 26.5016 22.0996 32.0016 22.0996C37.4016 22.0996 41.8016 26.4996 41.8016 31.9996C41.8016 37.3996 37.4016 41.8996 32.0016 41.8996Z"/>
                               <path d="M47 11.5996C45 11.5996 43.5 13.1996 43.5 15.0996C43.5 16.9996 45.1 18.5996 47 18.5996C48.9 18.5996 50.5 16.9996 50.5 15.0996C50.5 13.1996 49 11.5996 47 11.5996Z"/>
                               <path d="M46.9008 1.7998H17.1008C8.60078 1.7998 1.80078 8.5998 1.80078 17.0998V46.9998C1.80078 55.3998 8.70078 62.2998 17.1008 62.2998H47.0008C55.4008 62.2998 62.3008 55.3998 62.3008 46.9998V17.0998C62.3008 8.5998 55.4008 1.7998 46.9008 1.7998ZM57.8008 46.8998C57.8008 52.8998 53.0008 57.6998 47.0008 57.6998H17.1008C11.1008 57.6998 6.30078 52.8998 6.30078 46.8998V17.0998C6.30078 11.0998 11.2008 6.2998 17.1008 6.2998H46.9008C52.9008 6.2998 57.7008 11.1998 57.7008 17.0998V46.8998H57.8008Z"/>
                               </svg>
                               
                               
                       </a>
                   </div>
               @endif

               <!-- Youtube Button -->
               @if (strlen($youtube)>2)
                   <div class='item'>
                       <a href='{{ $youtube }}'>
                           <svg fill="#1C2033" width="24" height="24" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                               <path d="M61.7 17.0998C61 14.3998 58.9 12.2998 56.2 11.5998C51.4 10.2998 32 10.2998 32 10.2998C32 10.2998 12.6 10.2998 7.8 11.5998C5.1 12.2998 3 14.3998 2.3 17.0998C1 21.9998 1 31.9998 1 31.9998C1 31.9998 1 42.0998 2.3 46.8998C3 49.5998 5.1 51.6998 7.8 52.3998C12.6 53.6998 32 53.6998 32 53.6998C32 53.6998 51.4 53.6998 56.2 52.3998C58.9 51.6998 61 49.5998 61.7 46.8998C63 42.0998 63 31.9998 63 31.9998C63 31.9998 63 21.9998 61.7 17.0998ZM25.8 41.2998V22.6998L41.9 31.9998L25.8 41.2998Z"/>
                           </svg>                                 
                       </a>
                   </div>
               @endif

               <!-- Twitter Button -->
               @if (strlen($twitter)>2)
                   <div class='item'>
                       <a href='{{ $twitter }}'>
                           <svg fill="#1C2033" width="24" height="24" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                               <path d="M21.4016 56.7998C14.7016 56.7998 8.20156 54.8998 2.80156 51.1998C1.90156 50.5998 1.60156 49.4998 1.90156 48.4998C2.30156 47.4998 3.30156 46.8998 4.30156 47.0998C5.10156 47.1998 5.90156 47.2998 6.70156 47.2998C9.60156 47.2998 12.4016 46.6998 15.0016 45.5998C11.8016 43.9998 9.20156 41.1998 8.10156 37.5998C7.90156 36.8998 8.00156 36.1998 8.40156 35.5998C5.60156 32.9998 4.00156 29.3998 4.00156 25.4998V25.3998C4.00156 24.5998 4.40156 23.8998 5.10156 23.4998C5.20156 23.3998 5.30156 23.3998 5.40156 23.2998C4.50156 21.4998 4.00156 19.3998 4.00156 17.3998C4.00156 14.8998 4.60156 12.5998 5.80156 10.5998C6.20156 9.9998 6.80156 9.5998 7.60156 9.4998C8.30156 9.3998 9.10156 9.7998 9.50156 10.2998C14.3016 16.1998 21.2016 19.9998 28.6016 20.9998V20.8998C28.6016 13.3998 34.7016 7.2998 42.2016 7.2998C45.5016 7.2998 48.6016 8.4998 51.1016 10.6998C52.2016 10.2998 53.4016 9.4998 54.6016 8.7998C55.2016 8.3998 55.8016 7.9998 56.4016 7.6998C57.1016 7.2998 58.0016 7.2998 58.8016 7.7998C59.6016 8.2998 59.9016 9.0998 59.8016 9.8998C59.7016 10.4998 59.5016 11.8998 59.1016 13.3998C59.3016 13.3998 59.4016 13.3998 59.5016 13.3998C60.4016 13.1998 61.3016 13.5998 61.8016 14.3998C62.3016 15.1998 62.3016 16.0998 61.8016 16.8998C60.4016 18.9998 58.3016 20.3998 56.4016 21.6998C56.2016 21.7998 56.0016 21.9998 55.9016 22.0998C55.9016 22.1998 55.9016 22.2998 55.9016 22.3998C56.0016 39.1998 43.0016 56.7998 21.4016 56.7998ZM12.8016 50.9998C15.5016 51.7998 18.4016 52.1998 21.4016 52.1998C40.2016 52.1998 51.5016 36.8998 51.5016 22.0998C51.5016 21.6998 51.5016 21.2998 51.5016 20.8998C51.4016 20.0998 51.7016 19.2998 52.4016 18.7998C52.9016 18.3998 53.5016 17.9998 54.0016 17.5998C54.3016 17.3998 54.6016 17.1998 54.9016 16.9998C54.4016 16.7998 54.0016 16.2998 53.8016 15.7998C53.6016 15.2998 53.6016 14.6998 53.8016 14.1998C52.9016 14.5998 52.0016 14.8998 51.1016 15.0998C50.4016 15.1998 49.6016 14.9998 49.1016 14.3998C47.3016 12.4998 45.0016 11.4998 42.5016 11.4998C37.5016 11.4998 33.4016 15.5998 33.4016 20.5998C33.4016 21.1998 33.5016 21.8998 33.6016 22.6998C33.7016 23.3998 33.6016 24.0998 33.1016 24.5998C32.6016 25.0998 32.0016 25.3998 31.3016 25.3998C22.8016 24.9998 14.8016 21.4998 8.80156 15.5998C8.70156 16.0998 8.70156 16.5998 8.70156 17.0998C8.70156 20.1998 10.2016 22.9998 12.8016 24.6998C13.6016 25.2998 14.0016 26.2998 13.7016 27.2998C13.4016 28.2998 12.5016 28.8998 11.5016 28.8998C10.8016 28.8998 10.0016 28.7998 9.30156 28.5998C10.4016 31.3998 12.9016 33.5998 16.0016 34.1998C17.0016 34.3998 17.8016 35.2998 17.8016 36.3998C17.8016 37.4998 17.1016 38.3998 16.1016 38.5998C15.5016 38.7998 14.8016 38.8998 14.3016 38.9998C16.0016 40.9998 18.4016 42.1998 21.1016 42.1998C22.1016 42.1998 22.9016 42.7998 23.2016 43.6998C23.5016 44.5998 23.2016 45.5998 22.4016 46.1998C19.3016 48.5998 16.2016 50.1998 12.8016 50.9998Z"/>
                           </svg>
                       </a>
                   </div>
               @endif  


               <!-- Website Button -->
               @if (strlen($website)>2)
                   <div class='item'>
                       <a href='{{ $website }}'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-globe">
                        <circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line>
                        
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                       </a>
                   </div>
               @endif  
               
               <!-- Phone button -->
               @if (strlen($phone)>2)
                   <div class='item'>
                       <a href='tel:{{ $phone }}'>
                          
                           <svg fill="#1C2033" width="24" height="24" version="1.1" id="lni_lni-phone" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                               y="0px" viewBox="0 0 64 64" style="enable-background:new 0 0 64 64;" xml:space="preserve">
                           <g>
                               <path d="M48.6,60.9c-8.1,0-19.6-5.8-30.2-15.8C4,31.3-2.1,15.9,4.3,9.1c0.3-0.3,0.7-0.6,1.1-0.8l8.1-4.6c2.1-1.2,4.7-0.6,6.1,1.4
                                   l5.9,8.4c0.7,1,1,2.2,0.7,3.4C26,18,25.3,19,24.3,19.7L20.8,22c2.7,3.9,10,13.6,21.6,20.5c0.1,0.1,0.2,0,0.2,0l2.5-3.4
                                   c1.4-1.9,4.1-2.4,6.2-1.1l8.8,5.6c2.1,1.3,2.7,4,1.4,6.1l-4.8,7.7c-0.3,0.4-0.6,0.8-0.9,1.1C54,60.1,51.5,60.9,48.6,60.9z
                                   M15.8,7.6C15.8,7.6,15.7,7.6,15.8,7.6l-8.2,4.6c-3.8,4.1,0.9,17.2,14,29.6c13.3,12.6,27,17.1,31.4,13.3l0,0c0,0,0,0,0,0l4.8-7.7
                                   l-8.8-5.6c-0.1,0-0.2,0-0.2,0l-2.5,3.4c-1.4,1.9-4.1,2.4-6.1,1.2c-12.5-7.5-20.3-17.9-23.1-22c-0.7-1-0.9-2.2-0.7-3.3
                                   c0.2-1.2,0.9-2.2,1.9-2.8l3.5-2.3l-5.8-8.3C15.9,7.7,15.8,7.6,15.8,7.6z"/>
                           </g>
                           </svg>

                       </a>
                   </div>
               @endif  
                </div>


               


            </nav>
        </div>
    </div>
</div>

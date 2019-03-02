namespace :tags do
  desc 'Update tags'

  # Tags that are being used as both 'New or Keep' tags as well as 'old' tags
  keep_tags = ['American Revolution','Civil War', 'Early National Period', 'Gilded Age',
               'Jacksonian Era', 'Japanese Internment', 'Mesoamerican', 'Reform', 'Slavery',
               'Technology','Civil Rights', 'Harlem Renaissance', 'Mythology']

  # old_tags for new_tag 'American Revolution'
  old_tags_1 = ['Founding Myths', 'Revolution']

  # old_tags for new_tag 'Civil War'
  old_tags_2 = ['Brady']

  # old_tags for new_tag 'Early National Period'
  old_tags_3 = ['Taxes']

  # old_tags for new_tag 'Gilded Age'
  old_tags_4 = ['Mills Factories Post Civil War', 'Stores', 'Overcivilization']

  # old_tags for new_tag 'Jacksonian Era'
  old_tags_5 = ['Sturbridge']

  # old_tags for new_tag 'Japanese Internment'
  old_tags_6 = ['W.W. II Internment', 'Japanese']

  # old_tags for new_tag 'Mesoamerican'
  old_tags_7 = ['Meso-American']

  # old_tags for new_tag 'Reform'
  old_tags_8 = ['Antebellum Reform', 'Social disorder and order to 1865', 'Progressive Era', 'Sweatshops',
                'Populism', 'Temperance and Prohibition', 'Temp to 1870\'s', 'Prohibition 1890-1930', 'Liquor',
                'Prostitution', 'Socialism', 'Pro feminist and suffrage', 'Anti feminist and suffrage', 'Utopias',
                'Utopias pre 1860']

 # old_tags for new_tag 'Slavery'
 old_tags_9 = ['Slavery and Abolition', 'Nineteenth Century Slavery', 'Slavery Misc.',
                'Slavery, Working, and Living Conditions', 'Slave Trading', 'Slaves Stereotypes',
                'Slaves Violence Resistance', 'Slaves Violence Resistance', 'Early American Slavery', 'Plantation Exterior',
                'Plantation Interior']

  # old_tags for new_tag 'Technology'
  old_tags_10 = ['Invention', 'Technique', 'Brooklyn Bridge', 'Hoover Dam']

  # old_tags for new_tag 'Civil Rights'
  old_tags_11 = []

  # old_tags for new_tag 'Harlem Renaissance'
  old_tags_12 = []

  # old_tags for new_tag 'Mythology'
  old_tags_13 = []

  old_tags_for_keep_list = [old_tags_1, old_tags_2, old_tags_3, old_tags_4, old_tags_5, old_tags_6, old_tags_7, old_tags_8,
                            old_tags_9, old_tags_10, old_tags_11, old_tags_12, old_tags_13]

  new_tags = ['17th Century', '18th Century', '19th Century', '20th Century', 'Abolition and Emancipation',
              'Advertising and Media', 'African Americans', 'Agriculture', 'Ancient History','Art and Architecture',
              'Business', 'Cities','Class and Status', 'Colombian and Other Expositions', 'Colonial America',
              'Developing Nations','Discovery and Conquest','Education', 'Environment','Family and Children',
              'Foreign Policy','Future Progress', 'Gender','Gravestones','Great Depression', 'Greece',
              'Immigration and Nativism', 'Industrialization and Urbanization', 'Institutions, social disorder, crime',
              'Labor','National Politics', 'Native Americans', 'Presidents', 'Race and Racism', 'Recreation',
              'Religion and Morals', 'Success','Suffrage', 'Symbols','The South','The West','Trade', 'Transportation',
              'Victorian Era', 'War', 'Women', 'Bicentennial', 'British Empire', 'Apartheid', 'Canada', 'Imperialism', 'Islam',
              'Maps', 'Medieval','Mexican', 'Politics and Government','Pop Culture','Satire and Comedy','Reconstruction']

  # old_tags for new_tag '17th Century'
  old_tags_14 = ['17th Century New England', 'Seventeenth Century', '17th Century Portraits', 'Early Images -- America',
                'Jamestown Memory','17th Century Gravestones', 'Plymouth', 'Research - Bartlett, Pilgrim Fathers',
                'Research - Burial Hill', 'Research - Dighton Rock', 'Research -- Bartlett, Pilgrim Fathers',
                'Research -- Burial Hill', 'Research -- Dighton Rock', 'Research -- Pilgrim Guides', 'Research -- Pilgrim Travel',
                'Research -- Plymouth Maps', 'Research -- Victorian Canopy', 'Research -- Misc. Monuments', 'Research', 'Statuary']

  # old_tags for new_tag '18th Century'
  old_tags_15 = ['18th Century Furniture', '18th Century Families', '18th Century Cities', 'Eighteenth Century',
                '18th Century Portraits', 'Social disorder and order to 1865', 'Early Images -- America', 'Early Virginia',
                'Colonial Revolution Period','Colonial Revival', 'Colonies', 'to 1763', 'Santa Barbara', 'Roanoke Memory',
                'Pre-Industrial Work - Misc.', 'Pre-Revolution', 'Williamsburg', 'American Revolution', 'Founding Myths',
                'Revolution','18th Century Gravestones']

  # old_tags for new_tag '19th Century'
  old_tags_16 = ['19th century Genre painting', 'Nineteenth Century', 'Nineteenth Century Children', 'Nineteenth Century Furniture',
                'Nineteenth Century Misc', 'Nineteenth and Twentieth Century', 'Business 19th century',
                'Social disorder and order to 1865', 'Early Images -- America', 'War of 1812', 'Abolition', 'Abolitionism',
                'Anti Abolition', 'Early National Period', 'Emancipation', 'Gilded Age', 'Mills Factories Post Civil War', 'Stores',
                'Overcivilization', 'Foreign Policy 19th Century', 'Mills Factories Post Civil War', 'Family to 1920',
                'Jacksonian Era','Sturbridge']

  # old_tags for new_tag '20th Century'
  old_tags_17 = ['Twentieth Century Misc.', 'Nineteenth and Twentieth Century', '1920s', '20\'s pop culture', 'Twenties', '1930s',
                'Pop Culture 1930\'s', '1940s', 'Forties', 'Forties Home Front', '1950s', '50\'s Misc.', '50\'s culture', 'Fifties',
                 '1960s', '60\'s War and Peace', 'Sixties', 'Art of 1930\'s', 'Fifties Art', 'Worlds Fair 1933', 'Twenties Art',
                 'Twentieth Century Painting', '1920s Advertising', 'Black Protest to 1950\'s', 'Black Protest, Riots since 1960',
                 'World War II', 'W.W.II', 'WWII News Coverage', 'W.W.II News coverage', 'World War I', 'W.W.I',
                 'Crime and criminals 20th century', 'Civil Rights', 'Foreign Policy 20th Century', 'Family since 1920', 'Gulf War',
                 'Harlem Renaissance']

  # old_tags for new_tag 'Abolition and Emancipation'
  old_tags_18 = ['Abolitionism', 'Anti Abolition', 'Emancipation']

  # old_tags for new_tag 'Advertising and Media'
  old_tags_19 = ['Ads by State', 'Ads. Public Issues', 'Ads. Misc.', 'Ads. and Success', '1920s Advertising', 'Cooption of styles',
                'Early Ads', 'Individualism, Technology', 'Ensemble', 'Personalization', 'Sears', 'Media', 'Propaganda',
                'War Posters']

  # old_tags for new_tag 'African Americans'
  old_tags_20 = ['Black Americans', 'Black Education and Self Help', 'Black Heroes', 'Black Misc.', 'Black Protest to 1950\'s',
                'Black Protest, Riots since 1960', 'Blacks in Cities', 'Blacks in Rural South', 'Blacks Reconstruction',
                'Free Blacks', 'Abolition', 'Abolitionism', 'Anti Abolition', 'Emancipation', 'Civil Rights', 'Slavery',
                'Slavery and Abolition', 'Nineteenth Century Slavery', 'Slavery Misc.', 'Slavery, Working, and Living Conditions',
                'Slave Trading', 'Slaves Stereotypes', 'Slaves Violence Resistance', 'Early American Slavery',
                'Plantation Exterior', 'Plantation Interior']

  # old_tags for new_tag 'Agriculture'
  old_tags_21 = ['Agrarian Reform', 'Agrarianism', 'Sharecroppers and rural US']

  # old_tags for new_tag 'Ancient History'
  old_tags_22 = ['Ancient Mexico', 'Ancient Peru', 'New Images', 'mythology', 'Greece Ancient']

  # old_tags for new_tag 'Art and Architecture'
  old_tags_23 = ['Art Style', 'Art of 1930\'s', '17th Century Portraits', '18th Century Portraits', 'Arts and Architecture',
                 'Fifties Art', 'Genre Painting', 'Impressionism', 'Women and Sculpture', 'Chromes', 'Twenties Art',
                 'Twentieth Century Painting', 'Durand', 'Eakins', 'Early Images -- America', 'Ashcan school', 'Bierstadt',
                 'Egyptomania', 'De Bry', 'John Singleton Copley', 'John White', 'Mary Cassatt', 'Naive Art',
                 'Illuminated Manuscripts', 'Sargent', 'Morse', 'Research -- Pilgrim Paintings', 'Whistler', 'Visual Cliché',
                 'William Harnett', 'Architecture', '17th Century Exteriors', '17th Century Interiors', '18th Century Exteriors',
                 '18th Century Interiors', 'Architecture Modern', 'Bathrooms', 'Arches', 'Nineteenth Century Interiors',
                 'Mission Revival', 'Craftsman', 'Reginald Marsh']

  # old_tags for new_tag 'Business'
  old_tags_24 = ['Business 19th century', 'Business 20th century', 'Corporate Image', 'Coca-Cola', 'Drummers', 'Logos']

  # old_tags for new_tag 'Cities'
  old_tags_25 = ['Charleston', 'Emerging industrial city', 'Early cities']

  # old_tags for new_tag 'Class and Status'
  old_tags_26 = ['Class Separation', 'Class Structure', 'Middle-Class Culture', 'Working Class Culture',
                 'Upper class ante bellum', 'Upper Class since 1865', 'Upper Class to 1865', 'Luxury']

  # old_tags for new_tag 'Colombian and Other Expositions'
  old_tags_27 = ['Columbian Exposition', 'Exhibition', 'Peale Museum', 'Worlds Fair 1933']

  # old_tags for new_tag 'Colonial America'
  old_tags_28 = ['Early Virginia', 'Colonial Revolution Period', 'Colonial Revival', 'Colonies', 'to 1763', 'Jamestown Memory',
                 'Santa Barbara', 'Roanoke Memory', 'Pre-Industrial Work - Misc.', 'Pre-Revolution', 'Williamsburg', 'Plymouth',
                 'Research - Bartlett, Pilgrim Fathers', 'Research - Burial Hill', 'Research - Dighton Rock',
                 'Research -- Bartlett, Pilgrim Fathers', 'Research -- Burial Hill', 'Research -- Dighton Rock',
                 'Research -- Pilgrim Guides', 'Research -- Pilgrim Travel', 'Research -- Plymouth Maps',
                 'Research -- Victorian Canopy', 'Research -- Misc. Monuments', 'Research', 'Statuary']

  # old_tags for new_tag 'Developing Nations'
  old_tags_29 = ['Decolonization']

  # old_tags for new_tag 'Discovery and Conquest'
  old_tags_30 = ['Expansion', 'Exploration', 'European Exploration', 'Frontier']

  # old_tags for new_tag 'Education'
  old_tags_31 = ['Higher Education']

  # old_tags for new_tag 'Environment'
  old_tags_32 = ['Environmental History', 'Beaches and Parks', 'Audubon', 'Conservation', 'Columbian Exchange', 'Eden Imagery',
                 'Nature and Civilization', 'Environmental movement', 'Landscape', 'Landscape, anti-urban', 'Oil',
                 'Grand Canyon', 'Parks and Cemeteries', 'Niagara', 'Painted Desert']

  # old_tags for new_tag 'Family and Children'
  old_tags_33 = ['Family since 1920', 'Family to 1920', 'Parents, Children, Families', 'Children', 'Child labor', 'Dolls',
                 'family', 'Aging']

  # old_tags for new_tag 'Foreign Policy'
  old_tags_34 = ['Foreign Policy 19th Century', 'Foreign Policy 20th Century', 'The US and Asia']

  # old_tags for new_tag 'Future Progress'
  old_tags_35 = ['Negative view', 'Positive view']

  # old_tags for new_tag 'Gender'
  old_tags_36 = ['Gender-Bending', 'Masculinity', 'Domesticity']

  # old_tags for new_tag 'Gravestones'
  old_tags_37 = ['17th Century Gravestones', '18th Century Gravestones']

  # old_tags for new_tag 'Great Depression'
  old_tags_38 = ['Bonus Army', 'Depression', 'Depression Misc.', 'New Deal', 'The Great Depression']

  # old_tags for new_tag 'Greece'
  old_tags_39 = ['Greece Ancient', 'Greece Modern']

    # old_tags for new_tag 'Immigration and Nativism'
  old_tags_40 = ['Immigrants', 'Immigrant Societies and Organization', 'Anti-Immigration', 'Emigration and Passage',
                 'Pro-Immigration', 'Arrival', 'Irish', 'Nativism', 'Anti Catholic Nativism', 'Chinese']

    # old_tags for new_tag 'Industrialization and Urbanization'
  old_tags_41 = ['Early mills and factories', 'Industrialization', 'Coal', 'Factory as symbol', 'Lowell', 'Market Economy',
                 'Saugus Iron Works', 'Pullman and Model Towns', 'Urbanization', 'Town and city planning', 'Urban misc.',
                 'Urban poverty', 'Urban gangs', 'prostitution', 'Socialism']

  # old_tags for new_tag 'Institutions, social disorder, crime'
  old_tags_42 = ['social disorder', 'Social disorder and order to 1865', 'Social Disorder', 'Crime and criminals 20th century']

  # old_tags for new_tag 'Labor'
  old_tags_43 = ['Labor Organizations and Leaders', 'Pullman and Model Towns', 'Work and Housing', 'Work and Workers',
                 'Working Conditions', 'Kohler Strike', 'Bread Lines, Urban Unemployment', 'Strikes and Violence',
                 'Child labor']

  # old_tags for new_tag 'National Politics'
  old_tags_44 = ['Americanization and Political Activity', 'Nationhood', 'National Events', 'US Nationalism', 'US Destiny',
                 'Statue of Liberty']

  # old_tags for new_tag 'Native Americans'
  old_tags_45 = ['Indian Assimilation', 'Indian Civilization', 'Indian Warfare', 'Indians', 'Indians in 20th century',
                 'Indian-White Relations Before Revolution', 'Indian-White Relations Since Revolution',
                 'New England Indians', 'Chaco', 'Miscellaneous, North American Indians', 'Mesa Verde',
                 'Navajo National Monument', 'Indian Portraits', 'Indian Americans', 'Paleo-Indian', 'Painted Desert']

  # old_tags for new_tag 'Presidents'
  old_tags_46 = ['Kennedy', 'Truman', 'Harding', 'Johnson', 'Nixon - Ford', 'Washington']

  # old_tags for new_tag 'Race and Racism'
  old_tags_47 = ['Race', 'Prejudice and Discrimination']

  # old_tags for new_tag 'Recreation'
  old_tags_48 = ['Popular recreation 1870-1920', 'Popular recreation since 1920', 'Popular recreation to 1865', 'Fitness',
                 'Outdoor Life', 'Recreation - upper class', 'Sports and Recreation', 'P.T. Barnum', 'Games',
                 'Games 19th century', 'Beaches and Parks', ]

  # old_tags for new_tag 'Religion and Morals'
  old_tags_49 = ['Pilgrim Pageants', 'Shakers', 'Revivalism since 1880', 'Salem Witch Trials', 'Judaism',
                 'Social Gospel and Missions', 'Catholicism', 'Holy Land', 'Moral lessons', 'Revolution to 1880']

  # old_tags for new_tag 'Success'
  old_tags_50 = ['Success 19th century', 'Success 20th century', 'Success Misc.']

  # old_tags for new_tag 'Suffrage'
  old_tags_51 = ['Pro feminist and suffrage', 'Anti feminist and suffrage']

  # old_tags for new_tag 'Symbols'
  old_tags_52 = ['Symbols of mass society']

  # old_tags for new_tag 'The South'
  old_tags_53 = ['Whites, non planters ante bellum', 'Southern Society', 'Post Antebellum south']

  # old_tags for new_tag 'The West'
  old_tags_54 = ['Western and Iron Mining', 'Cowboys', 'Painted Desert', 'Railroads']

  # old_tags for new_tag 'Trade'
  old_tags_55 = ['The US and Asia']

  # old_tags for new_tag 'Transportation'
  old_tags_56 = ['Railroad and steamboat', 'Railroads']

  # old_tags for new_tag 'Victorian Era'
  old_tags_57 = ['Victorian Death', 'Victorian Culture']

  # old_tags for new_tag 'War'
  old_tags_58 = ['War of 1812', 'World War II', 'W.W.II', 'WWII News Coverage', 'W.W.II News coverage', 'World War I',
                 'W.W.I', 'Women in the Revolution', 'Women in the Revolution Betty Ring, Needlework in', 'Women in war',
                 'Vietnam War', 'Vietnam', 'American Revolution', 'Revolution', 'Gulf War', 'Civil War', 'Brady',
                 'Japanese Internment', 'W.W. II Internment', 'Japanese']

  # old_tags for new_tag 'Women'
  old_tags_59 = ['Women and Sports', 'Women and Health', 'Women in labor movement', 'Women in the Revolution',
                 'Women in the Revolution Betty Ring, Needlework in', 'Women in war', 'Women\'s image',
                 'Women\'s liberation', 'Women\'s misc.', 'Women and Theater', 'Women\'s organizations', 'Women\'s work',
                 'College Women', 'New Woman']


  # old_tags for new_tag 'Bicentennial'
  old_tags_60 = []

  # old_tags for new_tag 'British Empire'
  old_tags_61 = []

  # old_tags for new_tag 'Apartheid'
  old_tags_62 = []

  # old_tags for new_tag 'Canada'
  old_tags_63 = []

  # old_tags for new_tag 'Imperialism'
  old_tags_64 = []

  # old_tags for new_tag 'Islam'
  old_tags_65 = []

  # old_tags for new_tag 'Maps'
  old_tags_66 = []

  # old_tags for new_tag 'Medieval'
  old_tags_67 = []

  # old_tags for new_tag 'Mexican'
  old_tags_68 = []

  # old_tags for new_tag 'Politics and Government'
  old_tags_69 = []

  # old_tags for new_tag 'Pop Culture'
  old_tags_70 = []

  # old_tags for new_tag 'Satire and Comedy'
  old_tags_71 = []

  # old_tags for new_tag 'Reconstruction'
  old_tags_72 = []

  old_tags_for_new_list = [old_tags_14, old_tags_15, old_tags_16, old_tags_17, old_tags_18, old_tags_19, old_tags_20, old_tags_21,
                           old_tags_22, old_tags_23, old_tags_24, old_tags_25, old_tags_26, old_tags_27, old_tags_28, old_tags_29,
                           old_tags_30, old_tags_31, old_tags_32, old_tags_33, old_tags_34, old_tags_35, old_tags_36, old_tags_37,
                           old_tags_38, old_tags_39, old_tags_40, old_tags_41, old_tags_42, old_tags_43, old_tags_44, old_tags_45,
                           old_tags_46, old_tags_47, old_tags_48, old_tags_49, old_tags_50, old_tags_51, old_tags_52, old_tags_53,
                           old_tags_54, old_tags_55, old_tags_56, old_tags_57, old_tags_58, old_tags_59, old_tags_60, old_tags_61,
                           old_tags_62, old_tags_63, old_tags_64, old_tags_65, old_tags_66, old_tags_67, old_tags_68, old_tags_69,
                           old_tags_70, old_tags_71, old_tags_72]

  new_and_keep_tags_lists = [keep_tags, new_tags]
  old_tags_lists = [old_tags_for_keep_list, old_tags_for_new_list]


  # Ensure all the new_tags exist
  task create: :environment do
    new_and_keep_tags_lists.each do |list|
      list.each do |tag|
        Topic.find_or_create_by(title: tag)
      end
    end
  end


  # Change old tags to new
  task reassign: :environment do
    old_tags_lists.each_with_index do |lists, row|

      lists.each_with_index do |list, col|
        new_topic = Topic.find_by(title: new_and_keep_tags_lists[row][col])
        new_topic_id = new_topic.id

        list.each do |tag|
          topic = Topic.find_by(title: tag)

          if topic.present?
            topic_assignments = TopicAssignment.where(topic_id: topic.id)

            if topic_assignments.present?
              topic_assignments.each do |topic_assignment|
                new_topic_assignments = TopicAssignment.where(["image_id = ? and topic_id = ?",
                                                                topic_assignment.image_id, new_topic_id])

                if new_topic_assignments.blank?
                  puts "New TopicAssignment for old topic '#{topic.title}' to new topic '#{new_topic.title}' for image_id #{topic_assignment.image_id}"
                  TopicAssignment.create!(image_id: topic_assignment.image_id, topic_id: new_topic_id)
                end
              end
            end
          end
        end
      end
    end
  end


  # Remove old tags from TopicAssignment and Topics table
  task delete: :environment do
    old_tags_lists.each_with_index do |lists, row|

      lists.each_with_index do |list, col|
        new_topic = Topic.find_by(title: new_and_keep_tags_lists[row][col])
        new_topic_id = new_topic.id

        list.each do |tag|
          unless keep_tags.include? tag
            old_tag = Topic.find_by(title: tag)

            if old_tag.present?
              old_topics_assignments = TopicAssignment.where(topic_id: old_tag.id)

              if old_topics_assignments.present?
                old_topics_assignments.each do |topic_assignment|
                  new_topic_assignments_exists =  TopicAssignment.where(["image_id = ? and topic_id = ?",
                                                                          topic_assignment.image_id, new_topic_id])

                  if new_topic_assignments_exists.present?
                    puts "Remove old tag '#{old_tag.title}' for image_id = #{topic_assignment.image_id}"
                    old_topics_assignments.destroy
                  end
                end
              end

              # check that old topic doesn't exist in TopicAssignment table
              old_topics_assignments = TopicAssignment.where(topic_id: old_tag.id)

              if old_topics_assignments.blank?
                old_tag.destroy
              end

            end
          end
        end
      end
    end
  end


  # Remove duplicate tags for a given image in TopicAssignment table
  task duplicate: :environment do
    new_and_keep_tags_lists.each do |list|
      list.each do |tag|
        topic = Topic.find_by(title: tag)

          new_topics_assignments = TopicAssignment.where(topic_id: topic.id)
          grouped = new_topics_assignments.group_by{ |assignment| assignment.image_id }

          grouped.values.each do |duplicates|
            first_one = duplicates.shift

            duplicates.each do |double|
              double.destroy
              puts "Remove duplicate topics '#{topic.title}' for an image #{double.image_id}"
            end
          end
      end
    end
  end

  task :all => [:create, :reassign, :delete, :duplicate]
end
